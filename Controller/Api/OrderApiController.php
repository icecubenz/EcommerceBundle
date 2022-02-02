<?php

namespace MauticPlugin\EcommerceBundle\Controller\Api;

use Mautic\ApiBundle\Controller\CommonApiController;
use MauticPlugin\EcommerceBundle\Entity\Order;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class OrderApiController extends CommonApiController
{
    const MODEL_ID = 'order.order';

    public function initialize(FilterControllerEvent $event)
    {
        $this->model            = $this->getModel(self::MODEL_ID);
        $this->entityClass      = Order::class;
        $this->entityNameOne    = 'order';
        $this->entityNameMulti  = 'orders';

        parent::initialize($event);
    }
}