<?php

namespace MauticPlugin\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\FormEntity;

class Googlefeed extends FormEntity
{
    private $id;

    private $url;

    private $status;

    private $shopId;

    private $userName;

    private $password;

    public static function loadMetadata(ORM\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('google_feed')
            ->setCustomRepositoryClass(GooglefeedRepository::class);

        $builder->addId();

        $builder->createField('url', 'text')
            ->columnName('url')
            ->build();

        $builder->createField('status', 'integer')
            ->columnName('status')
            ->build();

        $builder->createField('shopId', 'integer')
            ->columnName('shop_id')
            ->nullable()
            ->build();

        $builder->createField('userName', 'string')
            ->columnName('user_name')
            ->nullable()
            ->build();

        $builder->createField('password', 'string')
            ->columnName('password')
            ->nullable()
            ->build();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getShopId()
    {
        return $this->shopId;
    }

    public function setShopId($shopId)
    {
        $this->shopId = $shopId;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}