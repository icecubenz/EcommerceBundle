<?php

namespace MauticPlugin\EcommerceBundle\Model;

use MauticPlugin\EcommerceBundle\Entity\Googlefeed;
use MauticPlugin\EcommerceBundle\Form\Type\GooglefeedType;
use Mautic\CoreBundle\Model\FormModel;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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

    public function getEntity($id = null)
    {
        if (null === $id) {
            $entity = new Googlefeed();
            return $entity;
        }
    }

    public function createForm($entity, $formFactory, $action = null, $options = [])
    {
        if (!$entity instanceof Googlefeed) {
            throw new MethodNotAllowedHttpException(['Googlefeed']);
        }

        if (!empty($action)) {
            $options['action'] = $action;
        }

        //$options['allow_extra_fields'] = true;

        return $formFactory->create(GooglefeedType::class, $entity, $options);
    }
}