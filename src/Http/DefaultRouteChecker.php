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

namespace BitExpert\SyliusForceCustomerLoginPlugin\Http;

final readonly class DefaultRouteChecker implements DefaultRouteCheckerInterface
{
    public function isDefaultRoute(string $route): bool
    {
        if (str_starts_with($route, '/_wdt') ||
            str_starts_with($route, '/_profiler') ||
            str_starts_with($route, '/api') ||
            str_starts_with($route, '/admin') ||
            str_contains($route, '/login') ||
            str_contains($route, '/forgotten-password') ||
            str_contains($route, '/register') ||
            str_contains($route, '/cart') ||
            str_contains($route, '/checkout') ||
            str_contains($route, '/ajax')) {
            return true;
        }

        return false;
    }
}
