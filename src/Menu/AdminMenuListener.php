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

namespace BitExpert\SyliusForceCustomerLoginPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $configurationMenu = $menu->getChild('configuration');
        if (null === $configurationMenu) {
            return;
        }

        $configurationMenu
            ->addChild('new-subitem', ['route' => 'bitexpert_sylius_forcelogin_admin_whitelist_entry_index'])
            ->setLabel('Force Login')
            ->setLabelAttribute('icon', 'key');
    }
}
