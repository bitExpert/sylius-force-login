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

class RegexMatcher implements StrategyInterface
{
    public function getType(): string
    {
        return 'regex';
    }

    public function isMatch(string $path, WhitelistEntry $whitelistEntry): bool
    {
        return (
            preg_match(
                sprintf(
                    '#^.*%s/?.*$#i',
                    $this->quoteRule($whitelistEntry->getUrlRule())
                ),
                $path
            ) === 1
        );
    }

    /**
     * Quote delimiter in whitelist entry rule
     */
    private function quoteRule(string $rule, string $delimiter = '#'): string
    {
        return str_replace($delimiter, \sprintf('\%s', $delimiter), $rule);
    }
}
