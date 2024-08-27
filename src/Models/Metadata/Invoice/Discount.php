<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Invoice;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class Discount
{
    /**
     * Discount percentage
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("percent"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Discount Percentage"),
    ]
    public string $percent = "";

    /**
     * Discount amount
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("amount"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Discount Amount"),
    ]
    public string $amount = "";

    /**
     * Discount type
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("type"),
        JMS\Type("string"),
        SPL\Field(desc: "Discount Type"),
        SPL\Choices(array(
            "percent" => "Percent",
            "amount" => "Amount",
        ))
    ]
    public string $type = "";

}