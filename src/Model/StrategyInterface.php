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

interface StrategyInterface
{
    public function getType(): string;

    public function getName(): string;

    public function isMatch(string $path, WhitelistEntry $whitelistEntry): bool;
}
