<?php

namespace MauticPlugin\EcommerceBundle\Security\Permissions;

use Mautic\CoreBundle\Security\Permissions\AbstractPermissions;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class EcommercePermissions.
 */
class EcommercePermissions extends AbstractPermissions
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ecommerce';
    }

    /**
     * {@inheritdoc}
     */
    public function definePermissions()
    {
        $this->addExtendedPermissions('products');
        $this->addExtendedPermissions('carts');
        $this->addExtendedPermissions('orders');
        $this->addStandardPermissions('googlefeed');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface &$builder, array $options, array $data)
    {
        $this->addExtendedFormFields('ecommerce', 'products', $builder, $data);
        $this->addExtendedFormFields('ecommerce', 'carts', $builder, $data);
        $this->addExtendedFormFields('ecommerce', 'orders', $builder, $data);
        $this->addStandardFormFields('ecommerce', 'googlefeed', $builder, $data);
    }
}
