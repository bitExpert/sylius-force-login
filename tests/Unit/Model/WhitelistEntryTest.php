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

namespace Model;

use BitExpert\SyliusForceCustomerLoginPlugin\Model\StaticMatcher;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\WhitelistEntry;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Channel;

class WhitelistEntryTest extends TestCase
{
    /**
     * @test
     */
    public function addChannelToWhitelist(): void
    {
        $channel = new class extends Channel {
            public function getId() {
                return 123;
            }
        };

        $whitelistEntry = new WhitelistEntry();
        $this->assertCount(0, $whitelistEntry->getChannels());

        $whitelistEntry->addChannel($channel);
        $this->assertCount(1, $whitelistEntry->getChannels());
        $this->assertTrue($whitelistEntry->hasChannel($channel));
    }

    /**
     * @test
     */
    public function addSameChannelToWhitelistOnlyOnce(): void
    {
        $channel = new class extends Channel {
            public function getId() {
                return 123;
            }
        };

        $whitelistEntry = new WhitelistEntry();
        $whitelistEntry->addChannel($channel);
        $this->assertCount(1, $whitelistEntry->getChannels());

        $whitelistEntry->addChannel($channel);
        $this->assertCount(1, $whitelistEntry->getChannels());
    }

    /**
     * @test
     */
    public function removeChannelFromWhitelist(): void
    {
        $channel = new class extends Channel {
            public function getId() {
                return 123;
            }
        };

        $whitelistEntry = new WhitelistEntry();
        $whitelistEntry->addChannel($channel);
        $this->assertCount(1, $whitelistEntry->getChannels());

        $whitelistEntry->removeChannel($channel);
        $this->assertCount(0, $whitelistEntry->getChannels());
    }

    /**
     * @test
     */
    public function removeChannelFromWhitelistMultipleTimesCausesNoError(): void
    {
        $channel = new class extends Channel {
            public function getId() {
                return 123;
            }
        };

        $whitelistEntry = new WhitelistEntry();
        $whitelistEntry->addChannel($channel);
        $this->assertCount(1, $whitelistEntry->getChannels());

        $whitelistEntry->removeChannel($channel);
        $this->assertCount(0, $whitelistEntry->getChannels());

        $whitelistEntry->removeChannel($channel);
        $this->assertCount(0, $whitelistEntry->getChannels());
    }
}
