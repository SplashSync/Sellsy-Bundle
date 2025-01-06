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
                $this->convertAmountToPercentage();
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
        if ("percent" == $this->type) {
            return (float) $this->value;
        }

        return 0.2;
    }

    private function convertAmountToPercentage(): void
    {
        //        $referencePrice = $this->getReferencePrice();
        $referencePrice = 100;

        if ($referencePrice > 0) {
            // Convert amount to percentage
            $this->value = (string)round((float)$this->value / $referencePrice * 100, 2);
        } else {
            // If no valid reference price, fallback to 0% discount
            $this->value = "0.00";
        }
        $this->type = "percent";
    }
}
