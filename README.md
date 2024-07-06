# Force Login Plugin for Sylius

[![Mastodon Follow](https://img.shields.io/mastodon/follow/109408681246972700?domain=https://rheinneckar.social)](https://rheinneckar.social/@bitexpert)

The **Force Login** Plugin for *Sylius* allows you to restrict which pages a visitor is
able to see. Visitors get redirected to the login page if the page is not marked visitable.
The **Force Login** Module for *Sylius* is especially useful for merchants serving only a specific
group of users, e.g. enterprise related business partners and need to ensure that only those users are
able to browse the website or the product catalog.

## Features:
* Force your guest visitors to log in first (or register), before allowing them to visit your pages and catalog
* Administration: Manage the whitelist rules by the GUI in the administration area
* Whitelisting: Define url rules as pattern to define which pages guest visitors can visit without logging in first
* Multistore-Support: Define if whitelist rules either apply globally or for specific stores

## Requirements:
* PHP 8.1 or higher
* Sylius 1.13 or higher

## Installation

1. Install the plugin via Composer
```bash
composer require bitexpert/sylius-force-login-plugin
```

2. Enable the plugin
```php
<?php
# config/bundles.php
return [
    // ...

    BitExpert\SyliusForceCustomerLoginPlugin\BitExpertSyliusForceCustomerLoginPlugin::class => ['all' => true],
];
```

3. Import config
```yaml
# config/packages/_sylius.yaml
imports:
    # ...

    - { resource: "@BitExpertSyliusForceCustomerLoginPlugin/Resources/config/config.yml" }
    
    # ...
```

4. Import routing
```yaml
# config/routes/bitexpert_sylius_force_login.yaml
bitexpert_sylius_forcelogin:
  resource: "@BitExpertSyliusForceCustomerLoginPlugin/Resources/config/admin_routing.yml"
  prefix: '/%sylius_admin.path_name%'
```

5. Update your database schema
```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

# User Guide

## How to use
The usage of the **Force Login** Module for *Sylius* is applied implicitly by redirecting visitors
if the called URI does not match any configured whitelisted url rules.

### Whitelisting

Whitelisting is based upon the usage of rules. The strategy selection defines how the rules are interpreted, [details are listed below](#strategies).
The following example shows, how to add a whitelist entry for the homepage (startpage).

In Sylius Admin navigate to Configuration > Force Login and use the *Create* button to create a new entry.

- Enter **Homepage** into the text field below the **Label** label.
- Enter **/** into the text field beside from the **Url Rule** label.
- Select the channel from the channel list below the **Channel** label which the rule should be active for.

Use the **Create** button to save the entry. After being redirected to the **Overview Grid**, the new
entry should appear to the list.

## How to configure

### Strategies

#### Static
Rule is used as a literal value and will be added onto the base url for matching. This is default behaviour.

#### RegEx
Rule is based on [regular expression](https://en.wikipedia.org/wiki/Regular_expression), and will be used for looking up matching anywhere in the current url.

#### Negated RegEx
Based on the RegEx strategy, but negates the result. Helpful if only a few pages should be restricted.

### Add custom strategy

The strategy configuration is meant to be extensible. You can create an own strategy by creating a new class that
implements the `\BitExpert\SyliusForceCustomerLoginPlugin\Model\StrategyInterface` interface and is tagged with 
`force_customer_login.url_strategy` in your service configuration.

## Tests

You can run the unit tests with the following command (requires dependency installation):

    ./vendor/bin/phpunit

## Contribution
Feel free to contribute to this module by reporting issues or create some pull requests for improvements.

## License
The **Force Login** Module for *Sylius* is released under the MIT license.
