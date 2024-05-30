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

namespace BitExpert\SyliusForceCustomerLoginPlugin\Events;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ForceLoginRequestEvent implements EventSubscriberInterface
{
    public function __construct(private Security $security)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        // whitelist "default" urls that should always work
        $pathInfo = $request->getPathInfo();
        if ($pathInfo === '/' ||
            str_starts_with($pathInfo, '/_wdt') ||
            str_starts_with($pathInfo, '/_profiler') ||
            str_starts_with($pathInfo, '/admin') ||
            str_contains($pathInfo, '/login') ||
            str_contains($pathInfo, '/register') ||
            str_contains($pathInfo, '/cart') ||
            str_contains($pathInfo, '/checkout') ||
            str_contains($pathInfo, '/ajax')) {
            return;
        }

        // for any other url query the security framework
        if (!$this->security->isGranted('pathInfo', $request)) {
            throw new AccessDeniedException();
        }
    }
}
