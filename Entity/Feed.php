<?php

namespace MauticPlugin\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\FormEntity;

class Feed extends FormEntity
{
    private $id;

    private $url;

    private $shopId;

    private $userName;

    private $password;

    public static function loadMetadata(ORM\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('feed');
        $builder->addId();

        $builder->createField('url', 'text')
            ->columnName('url')
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
}