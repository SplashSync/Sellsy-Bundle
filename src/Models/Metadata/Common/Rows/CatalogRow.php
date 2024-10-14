<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Common\Rows;

use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models\ProductRow;
use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class CatalogRow extends ProductRow
{
    public const DATATYPE = "catalog";

    /**
     * Row Related Service or Product.
     */
    #[
        Assert\Type(Related::class),
        JMS\SerializedName("related"),
        JMS\Type(Related::class),
        SPL\Field(type: SPL_T_ID."::Product", desc: "Catalog Product ID"),
        SPL\Microdata("http://schema.org/Product", "productID"),
    ]
    public ?Related $related = null;
}