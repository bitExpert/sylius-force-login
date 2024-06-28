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

namespace BitExpert\SyliusForceCustomerLoginPlugin\Doctrine;

use BitExpert\SyliusForceCustomerLoginPlugin\Doctrine\DBAL\Types\Strategy;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\NegatedRegexMatcher;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\RegexMatcher;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\StaticMatcher;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Middleware;
use Doctrine\DBAL\Types\Type;

class StrategyTypeConfigurationMiddleware implements Middleware
{
    public function wrap(Driver $driver): Driver
    {
        try {
            /** @var Strategy $type */
            $type = Type::getType(Strategy::NAME);
            $type->setStrategies([
                new StaticMatcher(),
                new RegexMatcher(),
                new NegatedRegexMatcher(),
            ]);
        } catch (\Exception $e) {
        }

        return $driver;
    }
}
