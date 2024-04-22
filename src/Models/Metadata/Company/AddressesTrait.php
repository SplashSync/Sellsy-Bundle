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

namespace Splash\Connectors\Sellsy\Models\Metadata\Company;

use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\Sellsy\Models\Metadata\Address;
use Splash\Metadata\Attributes as SPL;

/**
 * Manage Addresses for Companies && Contacts
 * On reading, addresses are fetched from Embed field
 */
trait AddressesTrait
{
    #[
        JMS\Exclude,
        SPL\SubResource(Address::class, write: true),
        SPL\Accessor(factory: "addInvoicingAddress"),
    ]
    public ?Address $invoicingAddress = null;

    #[
        JMS\Exclude,
        SPL\SubResource(Address::class, write: true),
        SPL\Accessor(factory: "addDeliveryAddress"),
    ]
    public ?Address $deliveryAddress = null;

    /**
     * Fetch Addresses from Embedded data on Post Deserialize
     */
    #[JMS\PostDeserialize()]
    public function fetchAddresses(): void
    {
        //====================================================================//
        // Transfer Addresses from Embedded to Object
        $this->invoicingAddress = $this->embed->invoicingAddress ?? null;
        $this->deliveryAddress = $this->embed->deliveryAddress ?? null;
    }

    /**
     * Register a New Invoicing Address
     */
    public function addInvoicingAddress(): Address
    {
        return $this->invoicingAddress = new Address();
    }

    /**
     * Register a New Delivery Address
     */
    public function addDeliveryAddress(): Address
    {
        return $this->deliveryAddress = new Address();
    }
}
