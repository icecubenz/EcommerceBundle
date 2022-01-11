<?php

namespace MauticPlugin\EcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\FormEntity;

class GooglefeedLog extends FormEntity
{
    private $id;

    private $message;

    private $feedId;

    public static function loadMetadata(ORM\ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('google_feed_log')
            ->setCustomRepositoryClass(GooglefeedLogRepository::class);

        $builder->addId();

        $builder->createField('message', 'text')
            ->columnName('message')
            ->build();

        $builder->createField('feedId', 'integer')
            ->columnName('feed_id')
            ->build();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getFeedId()
    {
        return $this->feedId;
    }

    public function setFeedId($id)
    {
        $this->feedId = $id;
    }
}