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

namespace Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models;

use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Discount;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Related;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

abstract class ProductRow extends AbstractRow
{
    /**
     * Row Reference.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("reference"),
        JMS\Type("string"),
        SPL\Field(desc: "Row reference"),
        SPL\Microdata("http://schema.org/Product", "sku"),
    ]
    public ?string $reference = null;

    /**
     * Row Description.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("description"),
        JMS\Type("string"),
        SPL\Field(desc: "Row description"),
        SPL\Microdata("http://schema.org/partOfInvoice", "description"),
    ]
    public ?string $description = null;

    /**
     * Row unit price excluding taxes.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("unit_amount"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_PRICE, desc: "Unit amount without tax"),
        SPL\Microdata("http://schema.org/PriceSpecification", "price"),
    ]
    public ?string $unitAmount = null;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("quantity"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_INT, name: "Quantity", desc: "Item Quantity"),
        SPL\Microdata("http://schema.org/QuantitativeValue", "value"),
    ]
    public string $quantity;

    /**
     * Discount.
     */
    #[
        Assert\Type(Discount::class),
        JMS\SerializedName("discount"),
        JMS\Type(Discount::class),
        JMS\Groups(array("Read", "Write")),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Discount %"),
        SPL\Microdata("http://schema.org/Order", "discount"),
        SPL\Accessor(
            getter: "getDiscount",
            setter: "setDiscount"
        )
    ]
    public ?Discount $discount;

    /**
     * Product Row Tax ID.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("tax_id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Tax Name"),
        SPL\Microdata("http://schema.org/PriceSpecification", "valueAddedTaxName"),
    ]
    public int $taxId = 0;

    #[JMS\Exclude()]
    public ?Related $related = null;

    /**
     * Compute Product Row Checksum to Detect Changes
     */
    public function getChecksum(): string
    {
        return md5(serialize($this));
    }

    /**
     * Set Discount Data through an SPL Accessor
     *
     * @param ?Discount $discount
     *
     * @return self
     */
    public function setDiscount(?Discount $discount): self
    {
        // Process and set the discount
        $this->processDiscount($discount);

//        dd($this);

        return $this; // Fluent interface
    }

    /**
     * Process incoming discount data and set it properly
     *
     * @param ?Discount $discount
     *
     * @return void
     */
    private function processDiscount(?Discount $discount): void
    {
        if (null !== $discount) {
            // Handle the conversion if discount type is "amount"
            if ($discount->type === "amount") {
                $referencePrice = $this->getReferencePrice();
                if ($referencePrice > 0) {
                    // Convert amount to percentage
                    $discount->value = (string)round((float)$discount->value / $referencePrice * 100, 2);
                } else {
                    // If no valid reference price, fallback to 0% discount
                    $discount->value = "0.00";
                }
                // Set the type to "percent" after conversion
                $discount->type = "percent";
            }

            // Ensure `value` is formatted as a float string
            $discount->value = (string)round((float)$discount->value, 2);

            // Validate the discount type
            if ($discount->type !== "percent") {
                $discount->type = "percent";
            }
        }
        $this->discount = $discount;
    }

    /**
     * Get the Discount Data through an SPL Accessor
     *
     * @return ?Discount
     */
    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    /**
     * Mock method to get the reference price for discount conversion
     *
     *
     * @return float
     */
    private function getReferencePrice(): float
    {
        // Return the base price (mocked here)
        return 100.00; // Assume the item's original price is 100
    }
}