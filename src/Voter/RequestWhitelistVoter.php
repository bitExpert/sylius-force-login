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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class RequestWhitelistVoter implements VoterInterface
{
    public function __construct(
        private WhitelistEntryRepositoryInterface $repository,
        private ChannelContextInterface $channelContext,
        private string $locale,
    ) {
    }

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        if (!$subject instanceof Request) {
            return self::ACCESS_ABSTAIN;
        }

        $pathInfo = $this->removeLocaleFromPathInfo($subject);
        $whitelistEntries = $this->repository->findByChannel($this->channelContext->getChannel());
        foreach ($whitelistEntries as $whitelistEntry) {
            /** @var WhitelistEntry $whitelistEntry */
            $strategy = $whitelistEntry->getStrategy();
            if ($strategy->isMatch($pathInfo, $whitelistEntry)) {
                return self::ACCESS_GRANTED;
            }
        }

        return self::ACCESS_DENIED;
    }

    private function removeLocaleFromPathInfo(Request $subject): string
    {
        $count = 1;
        $pathInfo = $subject->getPathInfo();

        return str_replace('/' . $this->locale . '/', '/', $pathInfo, $count);
    }
}
