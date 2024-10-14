<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Invoice;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

trait DatesTrait
{
    /**
     * Invoice's date.
     */
    #[
        Assert\NotNull,
        Assert\Type("date"),
        JMS\SerializedName("date"),
        JMS\Type("DateTime<'Y-m-d'>"),
        SPL\Field(type: SPL_T_DATE, desc: "Date of the invoice"),
    ]
    public \DateTime $date;

//    /**
//     * Invoice's shipping date.
//     */
//    #[
//        Assert\Type("string<string>"),
//        JMS\SerializedName("shipping_date"),
//        JMS\Type("string<date>"),
//        SPL\Field(type: SPL_T_DATE, desc: "Shipping Date of the invoice"),
//    ]
//    public ?string $shippingDate = null;
//
//    /**
//     * Invoice's due date.
//     */
//    #[
//        Assert\NotNull,
//        Assert\Type("date"),
//        JMS\SerializedName("due_date"),
//        JMS\Type("string<date>"),
//        SPL\Field(type: SPL_T_DATE, desc: "Due Date of the invoice"),
//    ]
//    public string $dueDate = "";
}