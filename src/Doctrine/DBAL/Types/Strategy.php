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

use BitExpert\SyliusForceCustomerLoginPlugin\Model\NegatedRegexMatcher;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\RegexMatcher;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\StaticMatcher;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\StrategyInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class Strategy extends Type
{
    public const NAME = 'Strategy';

    public function getName()
    {
        return self::NAME;
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

        $staticMatcher = new StaticMatcher();
        $regexMatcher = new RegexMatcher();
        $negatedRegexMatcher = new NegatedRegexMatcher();

        if ($value === $staticMatcher->getType()) {
            return $staticMatcher;
        }

        if ($value === $regexMatcher->getType()) {
            return $regexMatcher;
        }

        if ($value === $negatedRegexMatcher->getType()) {
            return $negatedRegexMatcher;
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
