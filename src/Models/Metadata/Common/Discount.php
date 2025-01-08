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
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models\ProductRow;
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
        JMS\SerializedName("value"),
        JMS\Groups(array("Read", "Write", "Required")),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Discount Percentage"),
    ]
    public string $value;

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
     *
     * @param null|float $discount
     * @param ProductRow $row
     *
     * @return void
     */
    public function updateDiscount(?float $discount, ProductRow $row): void
    {
        if (null !== $discount) {
            // Handle the conversion if discount type is "amount"
            if ("amount" === $this->type) {
                $this->convertAmountToPercentage($row);
            }

            // Ensure `value` is formatted as a float string
            $this->value = (string)round((float)$this->value, 2);

            // Validate and ensure type is "percent"
            $this->type ??= "percent";
        }
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
        if ("amount" === $this->type && isset($splPrice['ht']) && $splPrice['ht'] > 0 && $qty > 0) {
            $referencePrice = (float) $splPrice['ht'] * $qty; // Total price for all items
            $amountDiscount = (float) $this->value * $qty; // Total discount for all items

            // Calculate and return the percentage
            return round(($amountDiscount / $referencePrice) * 100, 2);
        }

        // Fallback to 0 if necessary information is missing
        return 0.0;
    }

    /**
     * Convert fixed discount amount into a percentage-based discount.
     *
     * @param ProductRow $row The product row containing unit price and quantity information
     *
     * @return void
     */
    private function convertAmountToPercentage(ProductRow $row): void
    {
        // Get the unit price excluding tax from the ProductRow
        $unitPrice = isset($row->unitAmount)
            ? (float)$row->unitAmount // Ensure 'ht' is used for unit price
            : 0.0;
        $quantity = isset($row->quantity) ? (int)$row->quantity : 0;

        // Calculate the total reference price (unit price * quantity)
        $referencePrice = $unitPrice * $quantity;

        if ($referencePrice > 0) {
            // Convert the fixed discount amount to a percentage
            $this->value = (string)round(((float)$this->value / $referencePrice) * 100, 2);
        } else {
            // If the reference price is invalid, default to a 0% discount
            $this->value = "0.00";
        }

        // Update the discount type to "percent"
        $this->type = "percent";
    }
}
