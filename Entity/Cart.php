<?php

namespace MauticPlugin\EcommerceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\FormEntity;
use Mautic\LeadBundle\Entity\Lead;


class Cart extends FormEntity
{
    private $id;

    private $store_cart_id;

    private $store_id = 0;

    private $cart_url;

    private $total = 0;

    private $total_incl_tax = 0;

    private $tax = 0;

    private $lead;

    private $cartLines;

    private $order;

    public function __construct()
    {
        $this->cartLines = new ArrayCollection();
    }

    public static function loadMetadata(ORM\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder
            ->setTable('ecommerce_carts')
            ->setCustomRepositoryClass(CartRepository::class)
            ->addIndex(['store_cart_id'],'store_cart_id')
            ->addUniqueConstraint(['store_cart_id', 'store_id'], 'unique_cart');

        $builder->addId();

        $builder->addLead(true);

        $builder->createField('store_cart_id', 'integer')
            ->columnName('store_cart_id')
            ->build();
        
        $builder->createField('store_id', 'integer')
            ->columnName('store_id')
            ->build();

        $builder->createField('cart_url', 'text')
            ->columnName('cart_url')
            ->nullable()
            ->build();

        $builder->createField('total', 'float')
            ->columnName('total')
            ->build();

        $builder->createField('total_incl_tax', 'float')
            ->columnName('total_incl_tax')
            ->build();

        $builder->createField('tax', 'float')
            ->columnName('tax')
            ->build();

        $builder->createOneToMany('cartLines', CartLine::class)
            ->orphanRemoval()
            ->setIndexBy('id')
            ->mappedBy('cart')
            ->cascadeAll()
            ->fetchExtraLazy()
            ->build();

        $builder->createOneToOne('order', Order::class)
            ->mappedBy('cart')
            ->fetchExtraLazy()
            ->cascadeAll()
            ->build();
    }

    public static function loadApiMetadata(ApiMetadataDriver $metadata)
    {
        $metadata->addProperties(
                [
                    'id',
                    'store_cart_id',
                    'store_id',
                    'cart_url',
                    'total',
                    'total_incl_tax',
                    'tax',
                    'cartLines',
                ]
            )
            ->build();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStoreCartId()
    {
        return $this->store_cart_id;
    }

    public function setStoreCartId($id)
    {
        $this->store_cart_id = $id;
    }

    public function getStoreId()
    {
        return $this->store_id;
    }

    public function setStoreId($id)
    {
        $this->store_id = $id;
    }

    public function getCartUrl()
    {
        return $this->cart_url;
    }

    public function setCartUrl($url)
    {
        $this->cart_url = $url;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($value)
    {
        $this->total = $value;
    }

    public function getTotalInclTax()
    {
        return $this->total_incl_tax;
    }

    public function setTotalInclTax($value)
    {
        $this->total_incl_tax = $value;
    }

    public function getTax()
    {
        return $this->tax;
    }

    public function setTax($value)
    {
        $this->tax = $value;
    }

    public function getLead()
    {
        return $this->lead;
    }

    public function setLead(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function clearCartLines()
    {
        $this->cartLines = new ArrayCollection();
    }

    public function getCartLines()
    {
        return $this->cartLines;
    }

    public function addCartLine(CartLine $cartLine)
    {
        $this->cartLines[] = $cartLine;
    }

    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    public function getOrder()
    {
        return $this->order;
    }
}