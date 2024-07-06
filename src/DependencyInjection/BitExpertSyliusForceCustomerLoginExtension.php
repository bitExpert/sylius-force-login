<?php

/*
 * This file is part of the Sylius Force Customer Login package.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace BitExpert\SyliusForceCustomerLoginPlugin\DependencyInjection;

use BitExpert\SyliusForceCustomerLoginPlugin\Attribute\AsUrlStrategy;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class BitExpertSyliusForceCustomerLoginExtension extends AbstractResourceExtension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.xml');

        $this->registerResources('bitexpert_sylius_forcelogin', $config['driver'], $config['resources'], $container);

        $this->registerAutoconfiguration($container);
    }

    private function registerAutoconfiguration(ContainerBuilder $container): void
    {
        $container->registerAttributeForAutoconfiguration(
            AsUrlStrategy::class,
            static function (ChildDefinition $definition): void {
                $definition->addTag(AsUrlStrategy::SERVICE_TAG);
            },
        );
    }
}
