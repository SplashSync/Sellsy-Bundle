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

namespace Splash\Connectors\Sellsy\Models\Locator;

use Splash\Connectors\Sellsy\Services\ThirdParty\AddressUpdater;
use Splash\Connectors\Sellsy\Services\ThirdParty\ContactCompaniesManager;
use Symfony\Contracts\Service\Attribute\SubscribedService;

/**
 * Implement Sellsy Locator Access for Companies & Contacts Services
 */
trait LocatorThirdPartyTrait
{
    /**
     * Get Sellsy Address Updater — invoicing & delivery addresses sub-resources.
     */
    #[SubscribedService(AddressUpdater::class)]
    public function getAddressUpdater(): AddressUpdater
    {
        return $this->get(AddressUpdater::class);
    }

    /**
     * Get Sellsy Contact Companies Manager — links between contacts & companies.
     */
    #[SubscribedService(ContactCompaniesManager::class)]
    public function getContactCompaniesManager(): ContactCompaniesManager
    {
        return $this->get(ContactCompaniesManager::class);
    }
}
