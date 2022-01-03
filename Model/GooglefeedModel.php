<?php

namespace MauticPlugin\EcommerceBundle\Model;

use Mautic\CoreBundle\Model\FormModel;

class GooglefeedModel extends FormModel
{
    public function getRepository()
    {
        return $this->em->getRepository('EcommerceBundle:Googlefeed');
    }

    public function getPermissionBase()
    {
        return 'ecommerce:googlefeed';
    }
}