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

namespace Splash\Connectors\Sellsy\Services\Accounting;

use Splash\Connectors\Sellsy\Interfaces\SellsyConnectorAwareInterface;
use Splash\Connectors\Sellsy\Models\Connector\SellsyConnectorAwareTrait;
use Splash\Connectors\Sellsy\Models\Metadata\Invoice;
use Splash\Core\Client\Splash;

/**
 * Manage Invoices Status Transitions
 *
 * Sellsy has a single transition: a draft invoice is validated into the due
 * status, and becomes read only. Going back, or cancelling, has no endpoint.
 */
class InvoiceStatusManager implements SellsyConnectorAwareInterface
{
    use SellsyConnectorAwareTrait;

    /**
     * Apply the Validation asked on an Invoice
     */
    public function validate(Invoice $invoice): bool
    {
        //====================================================================//
        // Safety Check - Invoice is Stored & Waits for Validation
        if (empty($invoice->id) || !$invoice->isToValidate()) {
            return true;
        }
        //====================================================================//
        // Validate the Invoice
        $response = $this->connector->getConnexion()->post(
            sprintf("/invoices/%s/validate", $invoice->id),
            array()
        );
        if (!is_array($response)) {
            return Splash::log()->err(
                sprintf("Unable to validate Invoice %s.", $invoice->id)
            );
        }

        return Splash::log()->msg(
            sprintf("Invoice %s was validated: it is now read only on Sellsy.", $invoice->id)
        );
    }
}
