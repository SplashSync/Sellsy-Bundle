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

use Splash\Connectors\Sellsy\Services\Accounting\InvoiceStatusManager;
use Splash\Connectors\Sellsy\Services\Accounting\PaymentMethodsManager;
use Splash\Connectors\Sellsy\Services\Accounting\PaymentsManager;
use Splash\Connectors\Sellsy\Services\Accounting\RowsUpdater;
use Splash\Connectors\Sellsy\Services\Accounting\TaxManager;
use Symfony\Contracts\Service\Attribute\SubscribedService;

/**
 * Implement Sellsy Locator Access for Sales Documents Services
 */
trait LocatorAccountingTrait
{
    /**
     * Get Sellsy Tax Manager — account VAT rates, by id, label or rate.
     */
    #[SubscribedService(TaxManager::class)]
    public function getTaxManager(): TaxManager
    {
        return $this->get(TaxManager::class);
    }

    /**
     * Get Sellsy Rows Updater — lines of a sales document.
     */
    #[SubscribedService(RowsUpdater::class)]
    public function getRowsUpdater(): RowsUpdater
    {
        return $this->get(RowsUpdater::class);
    }

    /**
     * Get Sellsy Invoices Status Manager — validation of a draft invoice.
     */
    #[SubscribedService(InvoiceStatusManager::class)]
    public function getInvoiceStatusManager(): InvoiceStatusManager
    {
        return $this->get(InvoiceStatusManager::class);
    }

    /**
     * Get Sellsy Payments Manager — payments of an invoice, created then linked.
     */
    #[SubscribedService(PaymentsManager::class)]
    public function getPaymentsManager(): PaymentsManager
    {
        return $this->get(PaymentsManager::class);
    }

    /**
     * Get Sellsy Payment Methods Manager — account methods & their associations.
     */
    #[SubscribedService(PaymentMethodsManager::class)]
    public function getPaymentMethodsManager(): PaymentMethodsManager
    {
        return $this->get(PaymentMethodsManager::class);
    }
}
