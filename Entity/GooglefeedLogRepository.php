<?php

declare(strict_types=1);

namespace MauticPlugin\EcommerceBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

class GooglefeedLogRepository extends CommonRepository
{
    public function getTableAlias()
    {
        return 'gfl';
    }
}