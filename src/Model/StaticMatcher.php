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

namespace BitExpert\SyliusForceCustomerLoginPlugin\Model;

use BitExpert\SyliusForceCustomerLoginPlugin\Attribute\AsUrlStrategy;

#[AsUrlStrategy]
class StaticMatcher implements StrategyInterface
{
    public function getType(): string
    {
        return 'static';
    }

    public function getName(): string
    {
        return 'Static';
    }

    public function isMatch(string $path, WhitelistEntry $whitelistEntry): bool
    {
        return str_starts_with($path, $whitelistEntry->getUrlRule());
    }
}
