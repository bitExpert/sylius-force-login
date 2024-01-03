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

namespace Tests\BitExpert\SyliusForceCustomerLoginPlugin\Unit\Model;

use BitExpert\SyliusForceCustomerLoginPlugin\Model\RegexMatcher;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\WhitelistEntry;
use PHPUnit\Framework\TestCase;

class RegexMatcherTest extends TestCase
{
    /**
     * @test
     */
    public function checkMatcherType(): void
    {
        $matcher = new RegexMatcher();

        $this->assertEquals('regex', $matcher->getType());
    }

    /**
     * @test
     */
    public function routeIsRegexMatch(): void
    {
        $whitelistEntry = new WhitelistEntry();
        $whitelistEntry->setUrlRule('/taxons/t-shirts/*');

        $pathInfo = '/taxons/t-shirts/men/';

        $matcher = new RegexMatcher();

        $this->assertTrue($matcher->isMatch($pathInfo, $whitelistEntry));
    }

    /**
     * @test
     */
    public function routeIsNoRegexMatch(): void
    {
        $whitelistEntry = new WhitelistEntry();
        $whitelistEntry->setUrlRule('/taxons/t-shirts/*');

        $pathInfo = '/taxons/caps/simple/';

        $matcher = new RegexMatcher();

        $this->assertFalse($matcher->isMatch($pathInfo, $whitelistEntry));
    }
}
