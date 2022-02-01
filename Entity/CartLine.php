<?php

namespace MauticPlugin\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;

class CartLine
{
    private $id;

    private $cart;

    private $product;

    private $qty;

    public static function loadMetadata(ORM\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->addId();

        $builder
            ->setTable('ecommerce_cart_lines')
            ->setCustomRepositoryClass(CartLineRepository::class)
            ->addIndex(['cart_id'], 'cart_id')
            ->addUniqueConstraint(['cart_id','product_id'], 'unique_cart_line');

        $builder->createManyToOne('cart', Cart::class)
            ->inversedBy('cartLines')
            ->addJoinColumn('cart_id', 'id', true, false)
            ->build();

        $builder->createManyToOne('product', Product::class)
            ->addJoinColumn('product_id', 'id', true, false)
            ->build();

        $builder->createField('qty', 'float')
            ->columnName('qty')
            ->build();
    }

    public static function loadApiMetadata(ApiMetadataDriver $metadata)
    {
        $metadata->addProperties(
                [
                    'id',
                    'qty',
                    'product',
                ]
            )
            ->build();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCart()
    {
        return $this->cart;
    }

    public function setCart(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }
}