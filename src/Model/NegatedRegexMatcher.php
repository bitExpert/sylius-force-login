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

class NegatedRegexMatcher extends RegexMatcher
{
    public function getType(): string
    {
        return 'negated_regex';
    }

    public function getName(): string
    {
        return 'Negated Regex';
    }

    public function isMatch(string $path, WhitelistEntry $whitelistEntry): bool
    {
        return !parent::isMatch($path, $whitelistEntry);
    }
}
