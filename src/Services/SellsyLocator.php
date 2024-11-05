<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\Sellsy\Services;

use Psr\Container\ContainerInterface;
use Splash\Connectors\Sellsy\Models\Connector\SellsyConnectorAwareTrait;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Webmozart\Assert\Assert;

/**
 * Sellsy Connector Service Locator
 */
class SellsyLocator implements ServiceSubscriberInterface
{
    use SellsyConnectorAwareTrait;

    public function __construct(
        private ContainerInterface $locator,
    ) {
    }

    public static function getSubscribedServices(): array
    {
        return array(
            ScopesManager::class,
            TaxManager::class,
            AddressUpdater::class,
            ContactCompaniesManager::class,
            RowsUpdater::class,
            WebhooksManager::class,
            Invoice\PaymentsManager::class,
            Invoice\PaymentMethodsManager::class,
        );
    }

    /**
     * Get Sellsy Scopes Manager
     */
    public function getScopesManager(): ScopesManager
    {
        Assert::isInstanceOf(
            $service = $this->locator->get(ScopesManager::class),
            ScopesManager::class
        );

        return $service->configure($this->connector);
    }

    /**
     * Get Sellsy Tax Manager
     */
    public function getTaxManager(): TaxManager
    {
        Assert::isInstanceOf(
            $service = $this->locator->get(TaxManager::class),
            TaxManager::class
        );

        return $service->configure($this->connector);
    }

    /**
     * Get Sellsy Address Updater
     */
    public function getAddressUpdater(): AddressUpdater
    {
        Assert::isInstanceOf(
            $service = $this->locator->get(AddressUpdater::class),
            AddressUpdater::class
        );

        return $service->configure($this->connector);
    }

    /**
     * Get Sellsy Contact Companies Manager
     */
    public function getContactCompaniesManager(): ContactCompaniesManager
    {
        Assert::isInstanceOf(
            $service = $this->locator->get(ContactCompaniesManager::class),
            ContactCompaniesManager::class
        );

        return $service->configure($this->connector);
    }

    /**
     * Get Sellsy Row Updater
     */
    public function getRowsUpdater(): RowsUpdater
    {
        Assert::isInstanceOf(
            $service = $this->locator->get(RowsUpdater::class),
            RowsUpdater::class
        );

        return $service->configure($this->connector);
    }

    /**
     * Get Sellsy Webhooks Manager
     */
    public function getWebhooksManager(): WebhooksManager
    {
        Assert::isInstanceOf(
            $service = $this->locator->get(WebhooksManager::class),
            WebhooksManager::class
        );

        return $service->configure($this->connector);
    }

    /**
     * Get Sellsy Webhooks Manager
     */
    public function getPaymentsManager(): Invoice\PaymentsManager
    {
        Assert::isInstanceOf(
            $service = $this->locator->get(Invoice\PaymentsManager::class),
            Invoice\PaymentsManager::class
        );

        return $service->configure($this->connector);
    }

    /**
     * Get Sellsy Webhooks Manager
     */
    public function getPaymentMethodsManager(): Invoice\PaymentMethodsManager
    {
        Assert::isInstanceOf(
            $service = $this->locator->get(Invoice\PaymentMethodsManager::class),
            Invoice\PaymentMethodsManager::class
        );

        return $service->configure($this->connector);
    }
}
