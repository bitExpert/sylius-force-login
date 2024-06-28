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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RegisterDoctrineMiddlewareCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        $configDefinition = $container->getDefinition('doctrine.dbal.default_connection.configuration');

        $middlewareMethodCallArgs = $this->extractMethodCallArgs($configDefinition);
        $middlewareMethodCallArgs[0] = array_merge(
            $setMiddlewaresMethodCallArguments[0] ?? [],
            [new Reference('bitexpert.sylius_force_customer_login_plugin.doctrine.middleware')],
        );

        $configDefinition
            ->removeMethodCall('setMiddlewares')
            ->addMethodCall('setMiddlewares', $middlewareMethodCallArgs);
    }

    /** @return array[] */
    private function extractMethodCallArgs(Definition $definition): array
    {
        foreach ($definition->getMethodCalls() as $methodCall) {
            if ('setMiddlewares' === $methodCall[0]) {
                return $methodCall[1];
            }
        }

        return [];
    }
}
