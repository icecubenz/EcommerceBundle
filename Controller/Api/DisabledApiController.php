<?php

namespace MauticPlugin\EcommerceBundle\Controller\Api;

use Mautic\ApiBundle\Controller\CommonApiController;

class DisabledApiController extends CommonApiController
{
    public function editEntityAction($id)
    {
        return $this->notFound();
    }

    public function editEntitiesAction()
    {
        return $this->notFound();
    }

    public function newEntityAction()
    {
        return $this->notFound();
    }

    public function newEntitiesAction()
    {
        return $this->notFound();
    }
}