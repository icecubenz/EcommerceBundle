<?php

namespace MauticPlugin\EcommerceBundle\Form\Type;

use Mautic\CoreBundle\Form\EventListener\CleanFormSubscriber;
use Mautic\CoreBundle\Form\EventListener\FormExitSubscriber;
use Mautic\CoreBundle\Form\Type\FormButtonsType;
use Mautic\CoreBundle\Form\Type\YesNoButtonGroupType;
use MauticPlugin\EcommerceBundle\Entity\Googlefeed as GooglefeedEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GooglefeedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new CleanFormSubscriber([]));
        $builder->addEventSubscriber(new FormExitSubscriber('googlefeed', $options));

        $builder->add(
            'url',
            TextType::class,
            [
                'label'      => 'mautic.ecommerce.url',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => ['class' => 'form-control'],
                'required'   => true,
            ]
        );

        if (!empty($options['data']) && $options['data']->getId()) {
            $status = ($options['data']->getStatus() == 1) ? true : false;
        } else {
            $status = true;
        }

        $builder->add(
            'status',
            YesNoButtonGroupType::class,
            [
                'label'      => 'mautic.ecommerce.googlefeed.enabled',
                'label_attr' => ['class' => 'control-label'],
                'required'   => true,
                'data'       => $status,
            ]
        );

        $builder->add(
            'shopId',
            TextType::class,
            [
                'label'      => 'mautic.ecommerce.shopId',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => ['class' => 'form-control'],
                'required'   => false,
            ]
        );

        $builder->add(
            'userName',
            TextType::class,
            [
                'label'      => 'mautic.ecommerce.googlefeed.username',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => ['class' => 'form-control'],
                'required'   => false,
            ]
        );

        $builder->add(
            'password',
            TextType::class,
            [
                'label'      => 'mautic.ecommerce.googlefeed.password',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => ['class' => 'form-control'],
                'required'   => false,
            ]
        );

        $builder->add(
            'buttons',
            FormButtonsType::class,
            [
                'pre_extra_buttons' => [],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => GooglefeedEntity::class]);
    }

    public function getBlockPrefix()
    {
        return 'googlefeed';
    }
}