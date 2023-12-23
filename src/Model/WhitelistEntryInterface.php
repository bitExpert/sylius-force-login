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

use Sylius\Component\Channel\Model\ChannelsAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface WhitelistEntryInterface extends ResourceInterface, ChannelsAwareInterface
{
    public function getLabel(): string;

    public function setLabel(string $label): void;

    public function getUrlRule(): string;

    public function setUrlRule(string $urlRule): void;

    public function getStrategy(): StrategyInterface;

    public function setStrategy(StrategyInterface $strategy): void;
}
