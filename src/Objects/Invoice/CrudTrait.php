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

namespace Splash\Connectors\Sellsy\Objects\Invoice;

use Splash\Connectors\Sellsy\Models\Metadata as ApiModels;

trait CrudTrait
{
    /**
     * @inheritdoc
     */
    public function load(string $objectId): ?object
    {
        //====================================================================//
        // Load Remote Object
        $invoice = parent::load($objectId);
        //====================================================================//
        // Invoice Found
        if ($invoice instanceof ApiModels\Invoice) {
            //====================================================================//
            // Load Invoice Linked Payments
            $paymentsManager = $this->connector->getLocator()->getPaymentsManager();
            $invoice->loadPayments($paymentsManager->fetchPayments($objectId));
        }

        return $invoice;
    }

    /**
     * Update Request Object
     *
     * @param bool $needed Is This Update Needed
     *
     * @return null|string Object ID of False if Failed to Update
     */
    public function update(bool $needed): ?string
    {
        $objectId = $this->getObjectIdentifier();
        //====================================================================//
        // Execute Generic Update
        if ($this->object->allowDocumentUpdate()) {
            $objectId = parent::update($needed);
        }
        //====================================================================//
        // Update Invoice Payments
        if ($this->connector->isSandbox() || $this->object->allowPaymentsUpdate()) {
            $paymentsManager = $this->connector->getLocator()->getPaymentsManager();
            if (!$paymentsManager->updatePayments($this->object)) {
                return null;
            }
        }

        return $objectId;
    }
}
