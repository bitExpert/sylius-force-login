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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface;

class WhitelistEntry implements WhitelistEntryInterface
{
    protected ?int $id = null;

    /** @var Collection<int, ChannelInterface>|ChannelInterface[] */
    protected $channels;

    private string $label;

    private string $urlRule;

    private string $strategy;

    public function __construct()
    {
        $this->channels = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getStrategy(): StrategyInterface
    {
        $staticMatcher = new StaticMatcher();
        $regexMatcher = new RegexMatcher();
        $negatedRegexMatcher = new NegatedRegexMatcher();

        if ($this->strategy === $staticMatcher->getType()) {
            return $staticMatcher;
        }

        if ($this->strategy === $regexMatcher->getType()) {
            return $regexMatcher;
        }

        if ($this->strategy === $negatedRegexMatcher->getType()) {
            return $negatedRegexMatcher;
        }

        throw new \RuntimeException('Unsupported strategy!');
    }

    public function setStrategy(StrategyInterface $strategy): void
    {
        $this->strategy = $strategy->getType();
    }
}
