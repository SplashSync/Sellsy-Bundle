<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Companies;

use Splash\Connectors\Sellsy\Models\Metadata\Addresses\Addresses;
use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;

/**
 * Virtual/Temporary Storage for Companies Embedded Data
 */
class CompanyEmbed
{

    public static function getUriQuery(): string
    {
        return "?embed[]=invoicing_address&embed[]=delivery_address";
    }

    #[
        JMS\SerializedName("invoicing_address"),
        JMS\Type(Addresses::class),
    ]
    public ?Addresses $invoicingAddress = null;

    #[
        JMS\SerializedName("delivery_address"),
        JMS\Type(Addresses::class),
    ]
    public ?Addresses $deliveryAddress = null;

}