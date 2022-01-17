<?php

namespace MauticPlugin\EcommerceBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailSendEvent;
use MauticPlugin\EcommerceBundle\Entity\Product;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductSubscriber implements EventSubscriberInterface
{
    private static $contactFieldRegex = '{productfield=(.*?)}';

    private $em;

    private $productRepo;

    public function __construct(EntityManager $em)
    {
        $this->em          = $em;
        $this->productRepo = $this->em->getRepository(Product::class);
    }

    public static function getSubscribedEvents()
    {
        return [
            EmailEvents::EMAIL_ON_SEND    => ['onEmailGenerate', 0],
            EmailEvents::EMAIL_ON_DISPLAY => ['onEmailDisplay', 0],
        ];
    }

    public function onEmailDisplay(EmailSendEvent $event)
    {
        $this->onEmailGenerate($event);
    }

    public function onEmailGenerate(EmailSendEvent $event)
    {
        $content = $event->getContent();

        $tokenList    = [];
        $entityList   = [];
        $foundMatches = preg_match_all('/({|%7B)productfield=(.*?)(}|%7D)/', $content, $matches);

        if ($foundMatches) {
            foreach ($matches[2] as $key => $match) {
                $token = $matches[0][$key];

                if (isset($tokenList[$token])) {
                    continue;
                }

                $parts = explode('|', $match);
                if (empty($parts[0]) || empty($parts[1])) {
                    $tokenList[$token] = '';
                }

                $idOrSku = trim($parts[1]);
                $field = trim($parts[0]);

                if (isset($entityList[$idOrSku])) {
                    $entity = $entityList[$idOrSku];
                } else {
                    $id = $this->getEntityId($idOrSku);
                    if ($id) {
                        $entity = $this->productRepo->getEntity($id);
                    } else {
                        $entity = $this->productRepo->getEntity($idOrSku);
                        if ($entity && $entity->getId()) {
                            $idCheck = (string) $entity->getId();
                            if ($idCheck !== $idOrSku) {
                                $entity = null;
                            }
                        }
                    }
                    $entityList[$idOrSku] = ($entity === null) ? false : $entity;
                }

                if ($entity) {
                    $tokenList[$token] = self::getTokenValue($entity, $field);
                } else {
                    $tokenList[$token] = '';
                }
            }
        }

        if (count($tokenList)) {
            $event->addTokens($tokenList);
            unset($tokenList);
        }
    }

    private static function getTokenValue($entity, $name)
    {
        $methodMaps = [
            'id'                => 'getId',
            'name'              => 'getName',
            'sku'               => 'getProductId',
            'price'             => 'getPrice',
            'url'               => 'getUrl',
            'image_url'         => 'getImageUrl',
            'short_description' => 'getShortDescription',
            'long_description'  => 'getLongDescription'
        ];

        if (isset($methodMaps[$name])) {
            $method = $methodMaps[$name];
            return $entity->{$method}();
        }

        return '';
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
}