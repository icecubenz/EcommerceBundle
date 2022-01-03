<?php

declare(strict_types=1);

namespace MauticPlugin\EcommerceBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

class GooglefeedRepository extends CommonRepository
{
    public function getEntities(array $args = [])
    {
        $q = $this->createQueryBuilder('gf')->select('gf');
        $args['qb'] = $q;

        return parent::getEntities($args);
    }

    public function getTableAlias()
    {
        return 'gf';
    }
}