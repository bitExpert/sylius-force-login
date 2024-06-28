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

namespace BitExpert\SyliusForceCustomerLoginPlugin\Doctrine\DBAL\Types;

use BitExpert\SyliusForceCustomerLoginPlugin\Model\StrategyInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class Strategy extends Type
{
    public const NAME = 'Strategy';

    /** @var StrategyInterface[] */
    private array $strategies = [];

    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param StrategyInterface[] $strategies
     */
    public function setStrategies(array $strategies)
    {
        $this->strategies = $strategies;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (!is_string($value)) {
            return parent::convertToPHPValue($value, $platform);
        }

        foreach ($this->strategies as $strategy) {
            if ($value === $strategy->getType()) {
                return $strategy;
            }
        }

        throw new \RuntimeException('Unsupported strategy!');
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!($value instanceof StrategyInterface)) {
            return parent::convertToDatabaseValue($value, $platform);
        }

        return $value->getType();
    }
}
