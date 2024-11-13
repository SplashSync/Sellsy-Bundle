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
        return parent::load($objectId);
        //====================================================================//
        // Invoice Found
        //        if ($object instanceof ApiModels\Invoice) {
        //            //====================================================================//
        //            // Load Invoice Linked Payments
        //            $paymentsManager = $this->connector->getLocator()->getPaymentsManager();
        //            $object->loadPayments($paymentsManager->fetchPayments($objectId));
        //        }
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
        // Debug
        //        dd(
        //            $this,
        //            $this->visitor->getHydrator()->extract($this->object),
        //            $this->object->allowDocumentUpdate()
        //        );

        //====================================================================//
        // Execute Generic Update
        if ($this->object->allowDocumentUpdate()) {
            $objectId = parent::update($needed);
        }
        //====================================================================//
        // Update Invoice Payments
        if ($this->object->allowPaymentsUpdate()) {
            $paymentsManager = $this->connector->getLocator()->getPaymentsManager();
            if (!$paymentsManager->updatePayments($this->object)) {
                return null;
            }
        }

        //        dd($this->object, $this->visitor->getLastResponse());

        return $objectId;
    }
}
