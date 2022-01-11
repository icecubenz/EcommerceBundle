<?php

namespace MauticPlugin\EcommerceBundle\Command;

use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use MauticPlugin\EcommerceBundle\Entity\GooglefeedLog;
use MauticPlugin\EcommerceBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GooglefeedCommand extends ContainerAwareCommand
{
    private $stats = [
        'total'   => 0,
        'new'     => 0,
        'update'  => 0,
        'skipped' => 0,
    ];

    private $em;

    private $productRepo;

    private $logRepo;

    public function __construct(EntityManager $em)
    {
        parent::__construct();
        $this->em          = $em;
        $this->productRepo = $this->em->getRepository(Product::class);
        $this->logRepo     = $this->em->getRepository(GooglefeedLog::class);
    }

    protected function configure()
    {
        $this->setName('ecommerce:googlefeed')
            ->setDescription('Pull products from Google Feed');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entities = $this->getEntities();

        foreach ($entities as $entity) {
            try {
                $filepath = $this->download(
                    $entity->getUrl(),
                    $entity->getUserName(),
                    $entity->getPassword()
                );
                $this->parse($filepath);
                $this->clean($filepath);
                $this->logSuccess($entity->getId());
            } catch (\Exception $e) {
                $this->logError($e->getMessage(), $entity->getId());
            }
        }

        return 0;
    }

    protected function getEntities()
    {
        return $this->getContainer()
            ->get('mautic.model.factory')
            ->getModel('googlefeed')
            ->getEntities(
                [
                    'filter' => [
                        'where' => [
                            [
                                'col'  => 'gf.status',
                                'expr' => 'eq',
                                'val'  => 1,
                            ],
                        ],
                    ],
                ]
            );
    }

    protected function download($url, $username = null, $password = null)
    {
        $info = pathinfo($url);
        $basename = $info['basename'];
        $extension = $info['extension'];

        if ($extension != 'xml') {
            throw new \Exception('Invalid source file. Only \'.xml\' allowed.');
        }

        $options = [];
        if ($username || $password) {
            $options['auth'] = [$username, $password];
        }

        $tmpPath = realpath($this->getContainer()->get('mautic.factory')->getSystemPath('tmp'));
        $destination = $tmpPath . '/' . $basename;

        if (file_exists($destination)) {
            unlink($destination);
        }
        $options['sink'] = $destination;

        $client = new Client;
        $client->get($url, $options);

        return $destination;
    }

    protected function parse($filepath)
    {
        $xml = new \XMLReader();
        $xml->open($filepath, 'UTF-8');

        while ($xml->read()) {
            if ($xml->nodeType !== \XMLReader::ELEMENT) {
                continue;
            }

            $name = strtolower($xml->name);

            switch ($name) {
                case 'item':
                case 'entry':
                    $entity = $this->parseItemTag($xml->readOuterXml());
                    $save = true;
                    break;
                default:
                    $save = false;
            }

            if ($save) {
                $this->stats['total']++;
                $this->saveEntity($entity);
            }
        }
    }

    protected function parseItemTag($xmlStr = '')
    {
        $fieldMap = [
            'id'          => 'product_id',
            'title'       => 'name',
            'image_link'  => 'image_url',
            'link'        => 'url',
            'price'       => 'price',
            'description' => 'long_description',
        ];

        $xmlStr = str_replace('g:', '', $xmlStr);
        $nodes = simplexml_load_string($xmlStr);

        $entity = [];
        foreach ($nodes as $name => $value) {
            if (isset($fieldMap[$name])) {
                $value = (string) $value;
                $entity[$fieldMap[$name]] = trim($value);
            }
        }

        return $entity;
    }

    protected function saveEntity(array $data = [])
    {
        if (empty($data)) {
            $this->stats['skipped']++;
            return;
        }

        foreach ($data as $column => $value) {
            if (in_array($column, ['name', 'product_id']) && empty($value)) {
                $this->stats['skipped']++;
                return;
            }
        }

        $isNew = true;

        $id = $this->getEntityId($data['product_id']);
        if ($id) {
            $entity = $this->productRepo->getEntity($id);
            if ($entity && $entity->getId()) {
                $isNew = false;
            }
        }

        if ($isNew) {
            $entity = new Product;
            $entity->setDateAdded(new \DateTime());
        }

        $entity->setDateModified(new \DateTime());
        $entity->setName($data['name']);
        $entity->setProductId($data['product_id']);
        $entity->setImageUrl($data['image_url']);
        $entity->setUrl($data['url']);
        $entity->setPrice($data['price']);
        $entity->setLongDescription($data['long_description']);

        try {
            $this->productRepo->saveEntity($entity);
            $statName = $isNew ? 'new' : 'update';
            $this->stats[$statName]++;
        } catch (\Exception $e) {
            $this->stats['skipped']++;
        }
    }

    protected function getEntityId($productId)
    {
        $q = $this->em->getConnection()->createQueryBuilder();

        $q->select('p.id')->from(MAUTIC_TABLE_PREFIX.'products', 'p');
        $q->andWhere('p.product_id = :id')->setParameter('id', $productId);

        $results = $q->execute()->fetchAll();
        foreach ($results as $product) {
            if (isset($product['id'])) {
                return $product['id'];
            }
        }
        return false;
    }

    protected function clean($filepath)
    {
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    protected function logSuccess($feedId)
    {
        $entity = new GooglefeedLog;
        $entity->setDateAdded(new \DateTime());
        $entity->setDateModified(new \DateTime());
        $entity->setMessage($this->getSuccessMessage());
        $entity->setFeedId($feedId);

        $this->logRepo->saveEntity($entity);
    }

    protected function getSuccessMessage()
    {
        return sprintf(
            '[<span style="color: blue;">Success</span>] Total: %d. New: %d. Update: %d. Skipped: %d.',
            $this->stats['total'],
            $this->stats['new'],
            $this->stats['update'],
            $this->stats['skipped']
        );
    }

    protected function logError($errMsg, $feedId)
    {
        $entity = new GooglefeedLog;
        $entity->setDateAdded(new \DateTime());
        $entity->setDateModified(new \DateTime());
        $entity->setMessage($this->getErrorMessage($errMsg));
        $entity->setFeedId($feedId);

        $this->logRepo->saveEntity($entity);
    }

    protected function getErrorMessage($errMsg)
    {
        return sprintf('[<span style="color: red;">Error</span>] %s', $errMsg);
    }
}