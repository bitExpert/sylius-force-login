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

namespace BitExpert\SyliusForceCustomerLoginPlugin\Voter;

use BitExpert\SyliusForceCustomerLoginPlugin\Doctrine\ORM\WhitelistEntryRepositoryInterface;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\WhitelistEntry;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class RequestWhitelistVoter implements VoterInterface
{
    public function __construct(
        private WhitelistEntryRepositoryInterface $repository,
        private ChannelContextInterface $channelContext,
    ) {
    }

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        if (!is_string($subject)) {
            return self::ACCESS_ABSTAIN;
        }

        $whitelistEntries = $this->repository->findByChannel($this->channelContext->getChannel());
        foreach ($whitelistEntries as $whitelistEntry) {
            /** @var WhitelistEntry $whitelistEntry */
            $strategy = $whitelistEntry->getStrategy();
            if ($strategy->isMatch($subject, $whitelistEntry)) {
                return self::ACCESS_GRANTED;
            }
        }

        return self::ACCESS_DENIED;
    }
}
