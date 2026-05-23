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

use BitExpert\SyliusForceCustomerLoginPlugin\Form\Type\WhitelistEntryType;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\WhitelistEntry;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set(WhitelistEntryType::class)
        ->args([
            tagged_iterator('force_customer_login.url_strategy'),
            WhitelistEntry::class,
        ])
        ->tag('form.type');
};
