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

use BitExpert\SyliusForceCustomerLoginPlugin\Model\WhitelistEntryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface;

class WhitelistEntry implements WhitelistEntryInterface
{
    protected int $id;

    /** @var Collection|ChannelInterface[] */
    protected $channels;
    private string $label;
    private string $urlRule;
    private string $strategy;

    public function __construct()
    {
        $this->channels = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function hasChannel(ChannelInterface $channel): bool
    {
        return $this->channels->contains($channel);
    }

    public function addChannel(ChannelInterface $channel): void
    {
        if (!$this->hasChannel($channel)) {
            $this->channels->add($channel);
        }
    }

    public function removeChannel(ChannelInterface $channel): void
    {
        if ($this->hasChannel($channel)) {
            $this->channels->removeElement($channel);
        }
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getUrlRule(): string
    {
        return $this->urlRule;
    }

    public function setUrlRule(string $urlRule): void
    {
        $this->urlRule = $urlRule;
    }

    public function getStrategy(): string
    {
        return $this->strategy;
    }

    public function setStrategy(string $strategy): void
    {
        $this->strategy = $strategy;
    }
}
