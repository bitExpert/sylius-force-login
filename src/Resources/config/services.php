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

return static function (ContainerConfigurator $container) {
    $container->import('services/doctrine.php');
    $container->import('services/form.php');
    $container->import('services/grid.php');
    $container->import('services/menus.php');
    $container->import('services/security.php');
    $container->import('services/strategies.php');
};
