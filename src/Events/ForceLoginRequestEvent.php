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

use BitExpert\SyliusForceCustomerLoginPlugin\Http\DefaultRouteCheckerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final readonly class ForceLoginRequestEvent implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private DefaultRouteCheckerInterface $defaultRouteChecker,
        private string $locale,
    ) {
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
        $pathInfo = $this->removeLocaleFromPathInfo($request->getPathInfo());
        if ($this->defaultRouteChecker->isDefaultRoute($pathInfo)) {
            return;
        }

        // for any other url query the security framework
        if (!$this->security->isGranted('pathInfo', $pathInfo)) {
            throw new AccessDeniedException();
        }
    }

    private function removeLocaleFromPathInfo(string $pathInfo): string
    {
        $count = 1;

        return str_replace('/' . $this->locale . '/', '/', $pathInfo, $count);
    }
}
