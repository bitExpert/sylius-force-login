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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use BitExpert\SyliusForceCustomerLoginPlugin\Doctrine\StrategyTypeConfigurationMiddleware;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('bitexpert.sylius_force_customer_login_plugin.doctrine.middleware', StrategyTypeConfigurationMiddleware::class)
        ->args([tagged_iterator('force_customer_login.url_strategy')]);
};
