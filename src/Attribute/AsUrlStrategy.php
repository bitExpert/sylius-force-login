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

namespace BitExpert\SyliusForceCustomerLoginPlugin\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class AsUrlStrategy
{
    public const SERVICE_TAG = 'force_customer_login.url_strategy';
}
