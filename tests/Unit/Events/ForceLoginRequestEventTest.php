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
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ForceLoginRequestEventTest extends \PHPUnit\Framework\TestCase
{
    private Security $securityMock;
    private RequestEvent $eventMock;
    private ForceLoginRequestEvent $forceLoginRequestEvent;

    protected function setUp(): void
    {
        $this->securityMock = $this->createMock(Security::class);
        $this->eventMock = $this->createMock(RequestEvent::class);
        $this->forceLoginRequestEvent = new ForceLoginRequestEvent($this->securityMock);
    }

    /**
     * @test
     */
    public function requestEventIsIgnoredForSubRequest(): void
    {
        $this->eventMock->method('isMainRequest')->willReturn(false);
        $this->eventMock->expects($this->never())->method('getRequest');
        $this->securityMock->expects($this->never())->method('isGranted');

        $this->forceLoginRequestEvent->onKernelRequest($this->eventMock);
    }

    /**
     * @dataProvider whitelistedUrls
     */
    public function whitelistedUrlsAlwaysGrantAccess(string $url): void
    {
        $request = Request::create($url);

        $this->eventMock->method('isMainRequest')->willReturn(true);
        $this->eventMock->method('getRequest')->willReturn($request);
        $this->securityMock->method('isGranted')->with('pathInfo', $request)->willReturn(true);

        $this->forceLoginRequestEvent->onKernelRequest($this->eventMock);
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function urlWithNoAccessGrantedThrowsAccessDeniedException(): void
    {
        $this->expectException(AccessDeniedException::class);

        $url = '/taxons/caps/simple/';
        $request = Request::create($url);

        $this->eventMock->method('isMainRequest')->willReturn(true);
        $this->eventMock->method('getRequest')->willReturn($request);
        $this->securityMock->method('isGranted')->with('pathInfo', $request)->willReturn(false);

        $this->forceLoginRequestEvent->onKernelRequest($this->eventMock);
    }

    /**
     * @test
     */
    public function accessGrantedSucceeds(): void
    {
        $url = '/taxons/caps/with-pompons';
        $request = Request::create($url);

        $this->eventMock->method('isMainRequest')->willReturn(true);
        $this->eventMock->method('getRequest')->willReturn($request);
        $this->securityMock->method('isGranted')->with('pathInfo', $request)->willReturn(true);

        $this->forceLoginRequestEvent->onKernelRequest($this->eventMock);
        $this->assertTrue(true);
    }

    public function whitelistedUrls(): array
    {
        return [
            ['/'],
            ['/_wdt'],
            ['/profiler'],
            ['/admin'],
            ['/login'],
            ['/register'],
            ['/cart'],
            ['/checkout'],
            ['/ajax'],
        ];
    }
}
