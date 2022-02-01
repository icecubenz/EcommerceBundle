<?php

namespace MauticPlugin\EcommerceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\FormEntity;
use Mautic\LeadBundle\Entity\Lead;

class Order extends FormEntity
{
    private $id;

    private $store_order_id;

    private $store_id = 0;

    private $subtotal = 0;

    private $subtotal_incl_tax = 0;

    private $tax = 0;

    private $discount = 0;

    private $discount_incl_tax = 0;

    private $shipping = 0;

    private $shipping_incl_tax = 0;

    private $total = 0;

    private $total_incl_tax = 0;

    private $cart;

    private $lead;

    private $orderRows;

    public function __construct()
    {
        $this->orderRows = new ArrayCollection();
    }

    public static function loadMetadata(ORM\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder
            ->setTable('ecommerce_orders')
            ->setCustomRepositoryClass(OrderRepository::class)
            ->addIndex(['store_order_id'],'store_order_id')
            ->addUniqueConstraint(['store_order_id', 'store_id'], 'unique_order');

        $builder->addId();

        $builder->addLead(true);

        $builder->createField('store_order_id', 'integer')
            ->columnName('store_order_id')
            ->build();

        $builder->createField('store_id', 'integer')
            ->columnName('store_id')
            ->build();

        $builder->createField('subtotal', 'float')
            ->columnName('subtotal')
            ->build();

        $builder->createField('subtotal_incl_tax', 'float')
            ->columnName('subtotal_incl_tax')
            ->build();

        $builder->createField('tax', 'float')
            ->columnName('tax')
            ->build();

        $builder->createField('discount', 'float')
            ->columnName('discount')
            ->build();

        $builder->createField('discount_incl_tax', 'float')
            ->columnName('discount_incl_tax')
            ->build();

        $builder->createField('shipping', 'float')
            ->columnName('shipping')
            ->build();

        $builder->createField('shipping_incl_tax', 'float')
            ->columnName('shipping_incl_tax')
            ->build();

        $builder->createField('total', 'float')
            ->columnName('total')
            ->build();

        $builder->createField('total_incl_tax', 'float')
            ->columnName('total_incl_tax')
            ->build();

        $builder->createOneToMany('orderRows', OrderRow::class)
            ->orphanRemoval()
            ->setIndexBy('id')
            ->mappedBy('order')
            ->cascadeAll()
            ->fetchExtraLazy()
            ->build();

        $builder->createOneToOne('cart', Cart::class)
            ->inversedBy('order')
            ->addJoinColumn('cart_id', 'id')
            ->build();
    }

    public static function loadApiMetadata(ApiMetadataDriver $metadata)
    {
        $metadata->addProperties(
                [
                    'id',
                    'store_order_id',
                    'store_id',
                    'subtotal',
                    'subtotal_incl_tax',
                    'tax',
                    'discount',
                    'discount_incl_tax',
                    'shipping',
                    'shipping_incl_tax',
                    'total',
                    'total_incl_tax',
                    'orderRows',
                ]
            )
            ->build();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStoreOrderId()
    {
        return $this->store_order_id;
    }

    public function setStoreOrderId($id)
    {
        $this->store_order_id = $id;
    }

    public function getStoreId()
    {
        return $this->store_id;
    }

    public function setStoreId($id)
    {
        $this->store_id = $id;
    }

    public function getSubtotal()
    {
        return $this->subtotal;
    }

    public function setSubtotal($val)
    {
        $this->subtotal = $val;
    }

    public function getSubtotalInclTax()
    {
        return $this->subtotal_incl_tax;
    }

    public function setSubtotalInclTax($val)
    {
        $this->subtotal_incl_tax = $val;
    }

    public function getTax()
    {
        return $this->tax;
    }

    public function setTax($val)
    {
        $this->tax = $val;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscount($val)
    {
        $this->discount = $val;
    }

    public function getDiscountInclTax()
    {
        return $this->discount_incl_tax;
    }

    public function setDiscountInclTax($val)
    {
        $this->discount_incl_tax = $val;
    }

    public function getShipping()
    {
        return $this->shipping;
    }

    public function setShipping($val)
    {
        $this->shipping = $val;
    }

    public function getShippingInclTax()
    {
        return $this->shipping_incl_tax;
    }

    public function setShippingInclTax($val)
    {
        $this->shipping_incl_tax = $val;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($val)
    {
        $this->total = $val;
    }

    public function getTotalInclTax()
    {
        return $this->total_incl_tax;
    }

    public function setTotalInclTax($val)
    {
        $this->total_incl_tax = $val;
    }

    public function getCart()
    {
        return $this->cart;
    }

    public function setCart(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function getLead()
    {
        return $this->lead;
    }

    public function setLead(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function getOrderRows()
    {
        return $this->orderRows;
    }

    public function addOrderRow(OrderRow $orderRow)
    {
        $this->orderRows[] = $orderRow;
    }

    public function setOrderRows($orderRows)
    {
        $this->orderRows = $orderRows;
    }
}