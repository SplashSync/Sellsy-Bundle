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

/**
 * Virtual/Temporary Storage for Company Embedded Data
 */
class CompanyEmbed
{
    #[
        JMS\SerializedName("invoicing_address"),
        JMS\Type(Address::class),
    ]
    public ?Address $invoicingAddress = null;

    #[
        JMS\SerializedName("delivery_address"),
        JMS\Type(Address::class),
    ]
    public ?Address $deliveryAddress = null;

    public static function getUriQuery(): string
    {
        return "?embed[]=invoicing_address&embed[]=delivery_address";
    }
}
