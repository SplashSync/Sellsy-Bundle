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

namespace Splash\Connectors\Sellsy\Models\Metadata\Common;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Splash\Models\Helpers\PricesHelper;
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
        JMS\SerializedName("value"),
        JMS\Groups(array("Read", "Write", "Required")),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Discount Percentage"),
    ]
    public string $value = "0.00";

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
        JMS\Groups(array("Read", "Write", "Required")),
        SPL\Field(desc: "Discount Type"),
        SPL\Choices(array(
            "percent" => "Percent",
            "amount" => "Amount",
        ))
    ]
    public string $type = "percent";

    /**
     * Process incoming discount data and set it properly
     */
    public function updateDiscount(float $discount, ?array $splPrice, int $qty): void
    {
        // Handle the conversion if discount type is "amount"
        if (abs($discount - $this->getPercentile($splPrice, $qty)) <= 1E-3) {
            return;
        }

        $this->type = "percent";
        $this->value = (string) $discount;
    }

    /**
     * Process discount to get final Discount Percentile
     */
    public function getPercentile(?array $splPrice, int $qty): float
    {
        // Validate the discount type
        if ("percent" === $this->type) {
            return (float) $this->value;
        }

        // Calculate percentage based on $splPrice and $qty
        $taxExcludedPrice = $splPrice ? PricesHelper::taxExcluded($splPrice) : 0;

        if ("amount" === $this->type && $taxExcludedPrice > 0 && $qty > 0) {
            // Total price for all items
            $referencePrice = (float) $taxExcludedPrice * $qty;
            // Total discount for all items
            $amountDiscount = (float) $this->value;

            // Calculate and return the percentage
            return $amountDiscount / $referencePrice * 100;
        }

        // Fallback to 0 if necessary information is missing
        return 0.0;
    }
}
