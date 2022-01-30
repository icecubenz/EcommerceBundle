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

    private $store_id = 0;

    private $username;

    private $password;

    public static function loadMetadata(ORM\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('ecommerce_google_feed')
            ->setCustomRepositoryClass(GooglefeedRepository::class);

        $builder->addId();

        $builder->createField('url', 'text')
            ->columnName('url')
            ->build();

        $builder->createField('status', 'integer')
            ->columnName('status')
            ->build();

        $builder->createField('store_id', 'integer')
            ->columnName('store_id')
            ->build();

        $builder->createField('username', 'string')
            ->columnName('username')
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

    public function getStoreId()
    {
        return $this->store_id;
    }

    public function setStoreId($id)
    {
        $this->store_id = $id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getTitle()
    {
        return 'Google Feed';
    }
}