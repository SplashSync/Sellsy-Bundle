<?php

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
        return [
            ScopesManager::class,
            TaxManager::class,
            AddressUpdater::class,
            ContactCompaniesManager::class,
            RowsUpdater::class,
            WebhooksManager::class,
        ];
    }

    /**
     * Get Sellsy Scopes Manager
     */
    public function getScopesManager(): ScopesManager
    {
        /** @var ScopesManager $service */
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
        /** @var TaxManager $service */
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
        /** @var AddressUpdater $service */
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
        /** @var ContactCompaniesManager $service */
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
        /** @var RowsUpdater $service */
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
        /** @var WebhooksManager $service */
        Assert::isInstanceOf(
            $service = $this->locator->get(WebhooksManager::class),
            WebhooksManager::class
        );

        return $service->configure($this->connector);
    }
}