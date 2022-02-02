<?php

namespace MauticPlugin\EcommerceBundle\Controller\Api;

use MauticPlugin\EcommerceBundle\Entity\Order;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class OrderApiController extends DisabledApiController
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