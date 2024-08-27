<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Invoice;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class Amounts
{
    /**
     * Invoice's total amount excluding taxes.
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total_raw_excl_tax"),
        JMS\Type("string"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Total Amount of the invoice", group: "Totals"),
    ]
    public string $totalRawExclTax = "";

    /**
     * Invoice's total amount after discount excluding taxes.
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total_after_discount_excl_tax"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Total Amount of the invoice after discount", group: "Totals"),
    ]
    public string $totalAfterDiscountExclTax = "";

    /**
     * Invoice's total Packaging.
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total_packaging"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Total Packaging of the invoice"),
    ]
    public string $totalPackaging = "";

    /**
     * Invoice's total Shipping.
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total_shipping"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Total Shipping of the invoice"),
    ]
    public string $totalShipping = "";

    /**
     * Invoice's total excluding taxes.
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total_excl_tax"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Total Amount of the invoice excluding taxes"),
    ]
    public string $totalExclTax = "";

    /**
     * Invoice's total including taxes.
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total_incl_tax"),
        JMS\Type("string"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Total Amount of the invoice including taxes"),
    ]
    public string $totalInclTax = "";

    /**
     * Invoice's total remaining due including taxes.
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total_remaining_due_incl_tax"),
        JMS\Type("string"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Total Remaining Due of the invoice including taxes"),
    ]
    public string $totalRemainingDueInclTax = "";

    /**
     * Invoice's total primes including taxes.
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total_primes_incl_tax"),
        JMS\Type("string"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Total Primes of the invoice including taxes"),
    ]
    public string $totalPrimesInclTax = "";
}