<?php

namespace MauticPlugin\EcommerceBundle\Controller\Api;

use MauticPlugin\EcommerceBundle\Entity\Cart;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class CartApiController extends DisabledApiController
{
    const MODEL_ID = 'cart.cart';

    public function initialize(FilterControllerEvent $event)
    {
        $this->model            = $this->getModel(self::MODEL_ID);
        $this->entityClass      = Cart::class;
        $this->entityNameOne    = 'cart';
        $this->entityNameMulti  = 'carts';

        parent::initialize($event);
    }
}