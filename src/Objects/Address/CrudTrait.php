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

namespace Splash\Connectors\Sellsy\Objects\Address;

use Splash\Connectors\Sellsy\Models\Metadata as ApiModels;

/**
 * Sellsy Address Crud Actions
 */
trait CrudTrait
{
    /**
     * @inheritdoc
     */
    public function load(string $objectId): ?object
    {
        //====================================================================//
        // Load Remote Object
        $contact = parent::load($objectId);
        //====================================================================//
        // Move Addresses out of the Embedded data.
        // JMS did it on PostDeserialize: Symfony Serializer has no such hook,
        // so it is done here, where the object is known to be complete.
        if ($contact instanceof ApiModels\Contact) {
            $contact->fetchAddresses();
        }

        return $contact;
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
        //====================================================================//
        // Execute Generic Save
        $objectId = parent::update($needed);
        if (!$objectId) {
            return $objectId;
        }
        //====================================================================//
        // Update Delivery Address
        if ($this->isToUpdate("DeliveryAddress")) {
            $this->connector
                ->getLocator()
                ->getAddressUpdater()
                ->createOrUpdateDeliveryAddress($this->object)
            ;
        }
        //====================================================================//
        // Update Invoicing Address
        if ($this->isToUpdate("InvoicingAddress")) {
            $this->connector
                ->getLocator()
                ->getAddressUpdater()
                ->createOrUpdateInvoicingAddress($this->object)
            ;
        }

        return $objectId;
    }
}
