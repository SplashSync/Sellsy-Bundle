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

use Splash\Connectors\Sellsy\Models\Metadata\Address;
use Splash\Connectors\Sellsy\Models\Metadata\AddressDelivery;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Serializer\Attribute as Serializer;

/**
 * Manage Addresses for Companies
 *
 * On a company, the main address is the invoicing one: it carries the generic
 * postal address templates, whereas the delivery address is delivery scoped.
 * On reading, addresses are fetched from Embed field.
 */
trait AddressesTrait
{
    #[
        Serializer\Ignore,
        SPL\SubResource(Address::class, write: true),
        SPL\Accessor(factory: "addInvoicingAddress"),
    ]
    public ?Address $invoicingAddress = null;

    #[
        Serializer\Ignore,
        SPL\SubResource(AddressDelivery::class, write: true),
        SPL\Accessor(factory: "addDeliveryAddress"),
    ]
    public ?AddressDelivery $deliveryAddress = null;

    /**
     * Fetch Addresses from Embedded data on Post Deserialize
     */
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
    public function addDeliveryAddress(): AddressDelivery
    {
        return $this->deliveryAddress = new AddressDelivery();
    }
}
