<?php

namespace MauticPlugin\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;

class OrderRow
{
    private $id;

    private $order;

    private $product;

    private $price = 0;

    private $price_incl_tax = 0;

    private $tax = 0;

    private $qty = 0;

    private $row_total = 0;

    private $row_total_incl_tax = 0;

    public static function loadMetadata(ORM\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->addId();

        $builder
            ->setTable('ecommerce_order_rows')
            ->setCustomRepositoryClass(OrederRowRepository::class)
            ->addIndex(['order_id'], 'order_id_index');

        $builder->createManyToOne('order', Order::class)
            ->inversedBy('orderRows')
            ->addJoinColumn('order_id', 'id', true, false)
            ->build();

        $builder->createManyToOne('product', Product::class)
            ->addJoinColumn('product_id', 'id', true, false)
            ->build();

        $builder->createField('price', 'float')
            ->columnName('price')
            ->build();

        $builder->createField('price_incl_tax', 'float')
            ->columnName('price_incl_tax')
            ->build();

        $builder->createField('tax', 'float')
            ->columnName('tax')
            ->build();

        $builder->createField('qty', 'float')
            ->columnName('qty')
            ->build();

        $builder->createField('row_total', 'float')
            ->columnName('row_total')
            ->build();

        $builder->createField('row_total_incl_tax', 'float')
            ->columnName('row_total_incl_tax')
            ->build();
    }

    public static function loadApiMetadata(ApiMetadataDriver $metadata)
    {
        $metadata->addProperties(
                [
                    'id',
                    'price',
                    'price_incl_tax',
                    'tax',
                    'qty',
                    'row_total',
                    'row_total_incl_tax',
                    'product',
                ]
            )
            ->build();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($val)
    {
        $this->price = $val;
    }

    public function getPriceInclTax()
    {
        return $this->price_incl_tax;
    }

    public function setPriceInclTax($val)
    {
        $this->price_incl_tax = $val;
    }

    public function getTax()
    {
        return $this->tax;
    }

    public function setTax($val)
    {
        $this->tax = $val;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setQty($val)
    {
        $this->qty = $val;
    }

    public function getRowTotal()
    {
        return $this->row_total;
    }

    public function setRowTotal($val)
    {
        $this->row_total = $val;
    }

    public function getRowTotalInclTax()
    {
        return $this->row_total_incl_tax;
    }

    public function setRowTotalInclTax($val)
    {
        $this->row_total_incl_tax = $val;
    }
}