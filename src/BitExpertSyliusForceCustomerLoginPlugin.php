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

namespace BitExpert\SyliusForceCustomerLoginPlugin;

use BitExpert\SyliusForceCustomerLoginPlugin\DependencyInjection\Compiler\RegisterDoctrineMiddlewareCompilerPass;
use BitExpert\SyliusForceCustomerLoginPlugin\DependencyInjection\Compiler\RegisterDoctrineTypeCompilerPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class BitExpertSyliusForceCustomerLoginPlugin extends AbstractResourceBundle
{
    use SyliusPluginTrait;

    public function getSupportedDrivers(): array
    {
        return [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterDoctrineTypeCompilerPass());
        $container->addCompilerPass(new RegisterDoctrineMiddlewareCompilerPass());
    }
}
