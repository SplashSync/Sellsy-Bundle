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

namespace Splash\Connectors\Sellsy\Models\Metadata\Contact;

use Splash\Connectors\Sellsy\Models\Metadata\Address;
use Splash\Connectors\Sellsy\Models\Metadata\AddressInvoicing;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Serializer\Attribute as Serializer;

/**
 * Manage Addresses for Contacts
 *
 * On a contact, the main address is the delivery one: it carries the generic
 * postal address templates, whereas the invoicing address is billing scoped.
 * On reading, addresses are fetched from Embed field.
 */
trait AddressesTrait
{
    #[
        Serializer\Ignore,
        SPL\SubResource(Address::class, write: true),
        SPL\Accessor(factory: "addDeliveryAddress"),
    ]
    public ?Address $deliveryAddress = null;

    #[
        Serializer\Ignore,
        SPL\SubResource(AddressInvoicing::class, write: true),
        SPL\Accessor(factory: "addInvoicingAddress"),
    ]
    public ?AddressInvoicing $invoicingAddress = null;

    /**
     * Fetch Addresses from Embedded data on Post Deserialize
     */
    public function fetchAddresses(): void
    {
        //====================================================================//
        // Transfer Addresses from Embedded to Object
        $this->deliveryAddress = $this->embed->deliveryAddress ?? null;
        $this->invoicingAddress = $this->embed->invoicingAddress ?? null;
    }

    /**
     * Register a New Delivery Address
     */
    public function addDeliveryAddress(): Address
    {
        return $this->deliveryAddress = new Address();
    }

    /**
     * Register a New Invoicing Address
     */
    public function addInvoicingAddress(): AddressInvoicing
    {
        return $this->invoicingAddress = new AddressInvoicing();
    }
}
