<?php
namespace MauticPlugin\EcommerceBundle;

use Doctrine\DBAL\Schema\Schema;
use Mautic\CoreBundle\Factory\MauticFactory;
use Mautic\PluginBundle\Entity\Plugin;
use Mautic\PluginBundle\Bundle\PluginBundleBase;
use MauticPlugin\EcommerceBundle\DependencyInjection\Compiler\OverridePageModelPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EcommerceBundle extends PluginBundleBase
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new OverridePageModelPass());
    }

    public static function onPluginUpdate(Plugin $plugin, MauticFactory $factory, $metadata = null, Schema $installedSchema = null)
    {
        self::updatePluginSchema($metadata, $installedSchema, $factory);
    }
}