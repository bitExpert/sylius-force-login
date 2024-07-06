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

namespace BitExpert\SyliusForceCustomerLoginPlugin\DependencyInjection\Compiler;

use BitExpert\SyliusForceCustomerLoginPlugin\Doctrine\DBAL\Types\Strategy;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterDoctrineTypeCompilerPass implements CompilerPassInterface
{
    private const CONTAINER_TYPES_PARAMETER = 'doctrine.dbal.connection_factory.types';

    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter(self::CONTAINER_TYPES_PARAMETER)) {
            return;
        }

        $typeDefinition = $container->getParameter(self::CONTAINER_TYPES_PARAMETER);
        if (!isset($typeDefinition[Strategy::NAME])) {
            $typeDefinition[Strategy::NAME] = ['class' => Strategy::class];
        }

        $container->setParameter(self::CONTAINER_TYPES_PARAMETER, $typeDefinition);
    }
}
