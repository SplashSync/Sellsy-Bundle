<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

abstract class ProductRow extends AbstractRow
{
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("quantity"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_INT, desc: "Item Quantity"),
    ]
    public string $quantity;
}