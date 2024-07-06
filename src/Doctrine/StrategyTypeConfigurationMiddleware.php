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
use BitExpert\SyliusForceCustomerLoginPlugin\Model\StrategyInterface;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Middleware;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

class StrategyTypeConfigurationMiddleware implements Middleware
{
    /**
     * @param RewindableGenerator $strategies
     */
    public function __construct(private readonly iterable $strategies)
    {
    }

    public function wrap(Driver $driver): Driver
    {
        $strategies = [];
        foreach ($this->strategies as $strategy) {
            /** @var StrategyInterface $strategy */
            $strategies[$strategy->getType()] = $strategy;
        }

        try {
            /** @var Strategy $type */
            $type = Type::getType(Strategy::NAME);
            $type->setStrategies($strategies);
        } catch (\Exception $e) {
        }

        return $driver;
    }
}
