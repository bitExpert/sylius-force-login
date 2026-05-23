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

use BitExpert\SyliusForceCustomerLoginPlugin\Doctrine\ORM\WhitelistEntryRepository;
use BitExpert\SyliusForceCustomerLoginPlugin\Events\ForceLoginRequestEvent;
use BitExpert\SyliusForceCustomerLoginPlugin\Http\DefaultRouteChecker;
use BitExpert\SyliusForceCustomerLoginPlugin\Voter\RequestWhitelistVoter;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Symfony\Bundle\SecurityBundle\Security;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('bitexpert.sylius_force_customer_login_plugin.default_route_checker', DefaultRouteChecker::class);

    $services->set(ForceLoginRequestEvent::class)
        ->args([
            service(Security::class),
            service('security.token_storage'),
            service('bitexpert.sylius_force_customer_login_plugin.default_route_checker'),
            '%locale%',
        ])
        ->tag('kernel.event_subscriber');

    $services->set(RequestWhitelistVoter::class)
        ->args([
            service(WhitelistEntryRepository::class),
            service(ChannelContextInterface::class),
        ])
        ->tag('security.voter');
};
