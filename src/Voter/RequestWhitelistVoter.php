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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class RequestWhitelistVoter implements VoterInterface
{
    public function __construct(private WhitelistEntryRepositoryInterface $repository, private string $locale)
    {
    }

    public function vote(TokenInterface $token, mixed $subject, array $attributes)
    {
        if (!$subject instanceof Request) {
            return self::ACCESS_ABSTAIN;
        }

        $count = 1;
        $pathInfo = $subject->getPathInfo();
        $pathInfoWithOutLocale = str_replace('/'.$this->locale.'/', '/', $pathInfo, $count);

        $whitelistEntries = $this->repository->findAll();
        foreach($whitelistEntries as $whitelistEntry) {
            /** @var WhitelistEntry $whitelistEntry */
            if (str_contains($pathInfoWithOutLocale, $whitelistEntry->getUrlRule())) {
                return self::ACCESS_GRANTED;
            }
        }

        return self::ACCESS_DENIED;
    }
}
