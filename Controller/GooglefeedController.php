<?php

namespace MauticPlugin\EcommerceBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractStandardFormController;

class GooglefeedController extends AbstractStandardFormController
{
    protected function getControllerBase()
    {
        return 'EcommerceBundle:Googlefeed';
    }

    protected function getModelName()
    {
        return 'googlefeed';
    }

    public function getDefaultOrderColumn()
    {
        return 'id';
    }

    public function indexAction($page = 1)
    {
        return parent::indexStandard($page);
    }

    public function newAction()
    {
        return parent::newStandard();
    }

    public function editAction($objectId, $ignorePost = false)
    {
        return parent::editStandard($objectId, $ignorePost);
    }

    public function deleteAction($objectId)
    {
        return parent::deleteStandard($objectId);
    }

    public function batchDeleteAction()
    {
        return parent::batchDeleteStandard();
    }
}