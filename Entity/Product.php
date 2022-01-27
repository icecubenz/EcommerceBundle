<?php

namespace MauticPlugin\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\FormEntity;
use Mautic\LeadBundle\Entity\CustomFieldEntityTrait as CustomFieldEntityTrait;

class Product extends FormEntity {
    use CustomFieldEntityTrait;

    private $id;

    private $name;

    private $product_id;

    private $store_id;

    private $price;

    private $sale_price;

    private $tax_rate;

    private $image_url;

    private $url;

    private $short_description;

    private $description;

    private $mpn;

    private $brand;

    private $availability;

    private $availability_date;

    public static function loadMetadata(ORM\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder
            ->setTable('ecommerce_products')
            ->setCustomRepositoryClass(ProductRepository::class)
            ->addIndex(['product_id'], 'product_id')
            ->addUniqueConstraint(['product_id', 'store_id'], 'unique_product');

        $builder->addId();

        $builder->createField('name', 'text')
            ->columnName('name')
            ->build();

        $builder->createField('product_id', 'string')
            ->columnName('product_id')
            ->build();

        $builder->createField('store_id', 'integer')
            ->columnName('store_id')
            ->build();

        $builder->createField('price', 'string')
            ->columnName('price')
            ->nullable()
            ->build();

        $builder->createField('sale_price', 'string')
            ->columnName('sale_price')
            ->nullable()
            ->build();

        $builder->createField('tax_rate', 'string')
            ->columnName('tax_rate')
            ->nullable()
            ->build();

        $builder->createField('image_url', 'text')
            ->columnName('image_url')
            ->nullable()
            ->build();

        $builder->createField('url', 'text')
            ->columnName('url')
            ->nullable()
            ->build();

        $builder->createField('short_description', 'text')
            ->columnName('short_description')
            ->nullable()
            ->build();

        $builder->createField('description', 'text')
            ->columnName('description')
            ->nullable()
            ->build();

        $builder->createField('mpn', 'string')
            ->columnName('mpn')
            ->nullable()
            ->build();

        $builder->createField('brand', 'string')
            ->columnName('brand')
            ->nullable()
            ->build();

        $builder->createField('availability', 'string')
            ->columnName('availability')
            ->nullable()
            ->build();

        $builder->createField('availability_date', 'string')
            ->columnName('availability_date')
            ->nullable()
            ->build();
    }

    public static function loadApiMetadata(ApiMetadataDriver $metadata)
    {
        $metadata->addProperties(
                [
                    'id',
                    'name',
                    'product_id',
                    'store_id',
                    'price',
                    'sale_price',
                    'tax_rate',
                    'image_url',
                    'url',
                    'short_description',
                    'description',
                    'mpn',
                    'brand',
                    'availability',
                    'availability_date',
                ]
            )
            ->build();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getProductId()
    {
        return $this->product_id;
    }

    public function setProductId($productId)
    {
        $this->product_id = $productId;
    }

    public function getStoreId()
    {
        return $this->store_id;
    }

    public function setStoreId($storeId)
    {
        $this->store_id = $storeId;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getSalePrice()
    {
        return $this->sale_price;
    }

    public function setSalePrice($price)
    {
        $this->sale_price = $price;
    }

    public function setTaxRate($rate)
    {
        $this->tax_rate = $rate;
    }

    public function getTaxRate()
    {
        return $this->tax_rate;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function setImageUrl($imageUrl)
    {
        $this->image_url = $imageUrl;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getShortDescription()
    {
        return $this->short_description;
    }

    public function setShortDescription($shortDescription)
    {
        $this->short_description = $shortDescription;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getMpn()
    {
        return $this->mpn;
    }

    public function setMpn($mpn)
    {
        $this->mpn = $mpn;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    public function getAvailability()
    {
        return $this->availability;
    }

    public function setAvailability($status)
    {
        $this->availability = $status;
    }

    public function getAvailabilityDate()
    {
        return $this->availability_date;
    }

    public function setAvailabilityDate($date)
    {
        $this->availability_date = $date;
    }
}