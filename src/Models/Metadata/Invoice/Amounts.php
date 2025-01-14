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
        SPL\Field(type: SPL_T_DOUBLE, desc: "Total Amount of the invoice"),
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
        SPL\Field(type: SPL_T_DOUBLE, desc: "Total Amount of the invoice after discount"),
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
