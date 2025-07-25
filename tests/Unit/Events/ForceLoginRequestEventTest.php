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

namespace Tests\BitExpert\SyliusForceCustomerLoginPlugin\Unit\Events;

use BitExpert\SyliusForceCustomerLoginPlugin\Events\ForceLoginRequestEvent;
use BitExpert\SyliusForceCustomerLoginPlugin\Http\DefaultRouteChecker;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class ForceLoginRequestEventTest extends \PHPUnit\Framework\TestCase
{
    private Security $securityMock;

    private TokenStorageInterface $tokenStorage;

    private RequestEvent $eventMock;

    private ForceLoginRequestEvent $forceLoginRequestEvent;

    private DefaultRouteChecker $defaultRouteCheck;

    protected function setUp(): void
    {
        $locale = 'en';

        $this->securityMock = $this->createMock(Security::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->eventMock = $this->createMock(RequestEvent::class);
        $this->defaultRouteCheck = new DefaultRouteChecker();

        $this->forceLoginRequestEvent = new ForceLoginRequestEvent(
            $this->securityMock,
            $this->tokenStorage,
            $this->defaultRouteCheck,
            $locale,
        );
    }

    #[Test]
    public function requestEventIsIgnoredForSubRequest(): void
    {
        $this->eventMock->method('isMainRequest')->willReturn(false);
        $this->eventMock->expects($this->never())->method('getRequest');
        $this->securityMock->expects($this->never())->method('isGranted');

        $this->forceLoginRequestEvent->onKernelRequest($this->eventMock);
    }

    #[Test]
    public function requestEventIsIgnoredForLoggedInUser(): void
    {
        $user = $this->createMock(UserInterface::class);
        $token = new UsernamePasswordToken($user, 'test');

        $this->eventMock->method('isMainRequest')->willReturn(false);
        $this->eventMock->expects($this->never())->method('getRequest');
        $this->tokenStorage->method('getToken')->willReturn($token);
        $this->securityMock->expects($this->never())->method('isGranted');

        $this->forceLoginRequestEvent->onKernelRequest($this->eventMock);
    }

    #[Test]
    #[DataProvider('whitelistedUrls')]
    public function whitelistedUrlsAlwaysGrantAccess(string $url): void
    {
        $request = Request::create($url);

        $this->eventMock->method('isMainRequest')->willReturn(true);
        $this->eventMock->method('getRequest')->willReturn($request);
        $this->tokenStorage->method('getToken')->willReturn(null);
        $this->securityMock->method('isGranted')->with('pathInfo', $request)->willReturn(true);

        $this->forceLoginRequestEvent->onKernelRequest($this->eventMock);
        $this->assertTrue(true);
    }

    #[Test]
    #[DataProvider('whitelistedUrlsWithLocale')]
    public function whitelistedUrlsWithLocaleAlwaysGrantAccess(string $url): void
    {
        $request = Request::create($url);

        $this->eventMock->method('isMainRequest')->willReturn(true);
        $this->eventMock->method('getRequest')->willReturn($request);
        $this->tokenStorage->method('getToken')->willReturn(null);
        $this->securityMock->method('isGranted')->with('pathInfo', $url)->willReturn(true);

        $this->forceLoginRequestEvent->onKernelRequest($this->eventMock);
        $this->assertTrue(true);
    }

    #[Test]
    public function urlWithNoAccessGrantedThrowsAccessDeniedException(): void
    {
        $this->expectException(AccessDeniedException::class);

        $url = '/taxons/caps/simple/';
        $request = Request::create($url);

        $this->eventMock->method('isMainRequest')->willReturn(true);
        $this->eventMock->method('getRequest')->willReturn($request);
        $this->tokenStorage->method('getToken')->willReturn(null);
        $this->securityMock->method('isGranted')->with('pathInfo', $url)->willReturn(false);

        $this->forceLoginRequestEvent->onKernelRequest($this->eventMock);
    }

    #[Test]
    public function accessGrantedSucceeds(): void
    {
        $url = '/taxons/caps/with-pompons';
        $request = Request::create($url);

        $this->eventMock->method('isMainRequest')->willReturn(true);
        $this->eventMock->method('getRequest')->willReturn($request);
        $this->tokenStorage->method('getToken')->willReturn(null);
        $this->securityMock->method('isGranted')->with('pathInfo', $url)->willReturn(true);

        $this->forceLoginRequestEvent->onKernelRequest($this->eventMock);
        $this->assertTrue(true);
    }

    public static function whitelistedUrls(): array
    {
        return [
            ['/_wdt'],
            ['/_profiler'],
            ['/admin'],
            ['/login'],
            ['/register'],
            ['/cart'],
            ['/checkout'],
            ['/ajax'],
        ];
    }

    public static function whitelistedUrlsWithLocale(): array
    {
        return [
            ['/en'],
            ['/en/login'],
            ['/en/register'],
            ['/en/cart'],
            ['/en/checkout'],
        ];
    }
}
