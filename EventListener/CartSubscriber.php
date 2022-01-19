<?php

namespace MauticPlugin\EcommerceBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailSendEvent;
use Mautic\LeadBundle\Model\LeadModel;
use MauticPlugin\EcommerceBundle\Entity\Cart;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;

class CartSubscriber implements EventSubscriberInterface
{
    private static $matchCartBlockRegex = '/{%\s?CART_START\s?(.*?)\s?%}(.*?){%\s?CART_END\s?%}/ism';

    private $leadModel;

    private $em;

    private $cartRepo;

    private $twigEnv;

    public function __construct(EntityManager $em, LeadModel $leadModel)
    {
        $this->em        = $em;
        $this->cartRepo  = $this->em->getRepository(Cart::class);
        $this->twigEnv   = new Environment(new ChainLoader([new ArrayLoader([])]));
        $this->leadModel = $leadModel;
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
        $contact = $event->getLead();

        $content = preg_replace_callback_array([
            self::$matchCartBlockRegex => $this->processCartBlock($contact['id'])
        ], $event->getContent());

        $event->setContent($content);
    }

    private function processCartBlock($contactId)
    {
        return function ($matches) use ($contactId) {
            $params = $this->getParams($matches[1]);
            $params['lead_id'] = $contactId;

            $context = $this->getContext($params);

            $templateSource = $matches[2];
            $template = $this->twigEnv->createTemplate($templateSource);
            $renderedTemplate = $template->render($context);
            return $renderedTemplate;
        };
    }

    private function getParams($str = '')
    {
        $params = [];

        $str = trim($str);
        $parts = explode('=', $str);
        if (count($parts) == 2 && ($parts[0] == 'id') && is_numeric($parts[1])) {
            $params['id'] = (int) $parts[1];
        }
        if ($str == 'abandoned') {
            $params['abandoned'] = true;
        }

        return $params;
    }

    private function getContext($params = [])
    {
        $context = ['cart' => [], 'carts' => []];

        if (!empty($params['id'])) {
            $entity = $this->cartRepo->getEntity($params['id']);
            if ($entity && $entity->getId()) {
                $this->extractFromEntity($context, [$entity]);
            }
        } else {
            $lead = $this->leadModel->getEntity($params['lead_id']);
            if ($lead && $lead->getId()) {
                $entities = $this->cartRepo->getEntities([
                    'filter' => [
                        'force' => [
                            [
                                'column' => 'ca.lead',
                                'expr'   => 'eq',
                                'value'  => $lead,
                            ],
                        ],
                    ],
                    'orderBy'          => 'ca.id',
                    'orderByDir'       => 'DESC',
                    'ignore_paginator' => true
                ]);
                $this->extractFromEntity($context, $entities, $params['abandoned']);
            }
        }
        return $context;
    }

    private function extractFromEntity(&$context, $entities = [], $abandoned = false)
    {
        foreach ($entities as $entity) {
            if ($abandoned && !$entity->getCartUrl()) {
                continue;
            }

            $cart = [
                'id'    => $entity->getId(),
                'url'   => $entity->getCartUrl(),
                'items' => []
            ];
            foreach ($entity->getCartLines() as $line) {
                $cart['items'][] = [
                    'name'      => $line->getProduct()->getName(),
                    'price'     => $line->getProduct()->getPrice(),
                    'qty'       => $line->getQuantity(),
                    'sku'       => $line->getProduct()->getProductId(),
                    'url'       => $line->getProduct()->getUrl(),
                    'image_url' => $line->getProduct()->getImageUrl()
                ];
            }
            if (empty($context['cart'])) {
                $context['cart'] = $cart;
            }
            $context['carts'][] = $cart;
        }
    }
}