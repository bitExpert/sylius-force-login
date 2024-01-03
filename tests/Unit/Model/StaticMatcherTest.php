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

use BitExpert\SyliusForceCustomerLoginPlugin\Model\StaticMatcher;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\WhitelistEntry;
use PHPUnit\Framework\TestCase;

class StaticMatcherTest extends TestCase
{
    /**
     * @test
     */
    public function checkMatcherType(): void
    {
        $matcher = new StaticMatcher();

        $this->assertEquals('static', $matcher->getType());
    }

    /**
     * @test
     */
    public function routeIsStaticMatch(): void
    {
        $whitelistEntry = new WhitelistEntry();
        $whitelistEntry->setUrlRule('/taxons/t-shirts/men');

        $pathInfo = '/taxons/t-shirts/men/';

        $matcher = new StaticMatcher();

        $this->assertTrue($matcher->isMatch($pathInfo, $whitelistEntry));
    }

    /**
     * @test
     */
    public function routeIsNoStaticMatch(): void
    {
        $whitelistEntry = new WhitelistEntry();
        $whitelistEntry->setUrlRule('/taxons/t-shirts/men');

        $pathInfo = '/taxons/caps/simple/';

        $matcher = new StaticMatcher();

        $this->assertFalse($matcher->isMatch($pathInfo, $whitelistEntry));
    }
}
