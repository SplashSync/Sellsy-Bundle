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
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;

/**
 * Virtual/Temporary Storage for Contact Embedded Data
 *
 * On a contact, the main address is the delivery one: it uses the generic
 * address model, while the invoicing address gets the billing scoped one.
 */
class ContactEmbed
{
    #[
        Serializer\SerializedName("invoicing_address"),
        Serializer\Groups(SplGroups::DEFAULT),
    ]
    public ?AddressInvoicing $invoicingAddress = null;

    #[
        Serializer\SerializedName("delivery_address"),
        Serializer\Groups(SplGroups::DEFAULT),
    ]
    public ?Address $deliveryAddress = null;
}
