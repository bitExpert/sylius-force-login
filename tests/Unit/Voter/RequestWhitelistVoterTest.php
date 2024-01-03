<?php
/*
 * This file is part of the sylius-force-login package.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Tests\BitExpert\SyliusForceCustomerLoginPlugin\Unit\Voter;

use BitExpert\SyliusForceCustomerLoginPlugin\Doctrine\ORM\WhitelistEntryRepositoryInterface;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\StaticMatcher;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\StrategyInterface;
use BitExpert\SyliusForceCustomerLoginPlugin\Model\WhitelistEntry;
use BitExpert\SyliusForceCustomerLoginPlugin\Voter\RequestWhitelistVoter;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class RequestWhitelistVoterTest extends TestCase
{
    private WhitelistEntryRepositoryInterface $repository;
    private ChannelContextInterface $channelContext;
    private RequestWhitelistVoter $requestWhitelistVoter;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(WhitelistEntryRepositoryInterface::class);
        $this->channelContext = $this->createMock(ChannelContextInterface::class);
        $locale = 'en';

        $this->requestWhitelistVoter = new RequestWhitelistVoter(
            $this->repository,
            $this->channelContext,
            $locale
        );
    }

    /**
     * @test
     */
    public function voteAbstainForNonRequestSubjects()
    {
        $token = $this->createMock(TokenInterface::class);
        $subject = new WhitelistEntry();

        $result = $this->requestWhitelistVoter->vote($token, $subject, []);

        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $result);
    }

    /**
     * @test
     */
    public function voteGrantedWhenRequestUriMatchesWhitelistEntry()
    {
        $token = $this->createMock(TokenInterface::class);
        $channel = $this->createMock(ChannelInterface::class);

        $whitelistEntry = new WhitelistEntry();
        $whitelistEntry->setStrategy(new StaticMatcher());
        $whitelistEntry->setUrlRule('/taxons/caps/simple/');

        $request = $this->createMock(Request::class);
        $request->method('getPathInfo')->willReturn('/taxons/caps/simple/');

        $this->repository->method('findByChannel')->willReturn([$whitelistEntry]);
        $this->channelContext->method('getChannel')->willReturn($channel);

        $result = $this->requestWhitelistVoter->vote($token, $request, []);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    /**
     * @test
     */
    public function voteDeniedWhenRequestUriDoesNotMatchWhitelistEntry()
    {
        $token = $this->createMock(TokenInterface::class);
        $channel = $this->createMock(ChannelInterface::class);

        $whitelistEntry = new WhitelistEntry();
        $whitelistEntry->setStrategy(new StaticMatcher());
        $whitelistEntry->setUrlRule('/taxons/caps/with-pompons');

        $request = $this->createMock(Request::class);
        $request->method('getPathInfo')->willReturn('/taxons/caps/simple/');

        $this->repository->method('findByChannel')->willReturn([$whitelistEntry]);
        $this->channelContext->method('getChannel')->willReturn($channel);

        $result = $this->requestWhitelistVoter->vote($token, $request, []);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }
}
