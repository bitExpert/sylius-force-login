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

use BitExpert\SyliusForceCustomerLoginPlugin\Model\NegatedRegexMatcher;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\RegexMatcher;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\StaticMatcher;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->autoconfigure();

    $services->set(NegatedRegexMatcher::class);

    $services->set(RegexMatcher::class);

    $services->set(StaticMatcher::class);
};
