<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Invoice;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class DecimalNumber
{
    /**
     * Decimal Number Value for Unit Price
     *
     * @var int|null
     */
    #[
        Assert\NotNull,
        Assert\Type("int"),
        JMS\SerializedName("unit_price"),
        JMS\Type("int"),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Decimal Number Value for Unit Price"),
    ]
    public ?int $unitPrice = null;

    /**
     * Decimal Number Value for Quantity
     *
     * @var int|null
     */
    #[
        Assert\NotNull,
        Assert\Type("int"),
        JMS\SerializedName("quantity"),
        JMS\Type("int"),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Decimal Number Value for Quantity"),
    ]
    public ?int $quantity = null;

    /**
     * Main precision of estimate
     *
     * @var int
     */
    #[
        Assert\NotNull,
        Assert\Type("int"),
        JMS\SerializedName("main"),
        JMS\Type("int"),
        SPL\Field(type: SPL_T_INT, desc: "Main precision of estimate"),
    ]
    public int $main = 0;

}