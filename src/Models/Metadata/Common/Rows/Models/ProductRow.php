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
        SPL\Associations(array("quantity@rows")),
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
        SPL\Associations(array("quantity@rows")),
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
        SPL\Associations(array("quantity@rows")),
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
        ),
        SPL\Associations(array("quantity@rows")),
    ]
    public ?Discount $discount = null;

    /**
     * Product Row Tax ID.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("tax_id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Tax Name"),
        SPL\Microdata("http://schema.org/PriceSpecification", "valueAddedTaxName"),
        SPL\IsNotTested,
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
     * @param null|float $discount
     * @param null|array $splPrice
     *
     * @return self
     */
    public function setDiscount(?float $discount, ?array $splPrice): self
    {
        // No Discount, erase array
        if (empty($discount)) {
            $this->discount = null;

            return $this;
        }
        //====================================================================//
        // Ensure Discount Object Exists
        $this->discount ??= new Discount();
        //====================================================================//
        // Process and set the discount
        $this->discount->updateDiscount($discount, $splPrice, (int) $this->quantity);

        return $this;
    }

    /**
     * Get the Discount Data through an SPL Accessor
     */
    public function getDiscount(?array $splPrice): ?float
    {
        return $this->discount?->getPercentile($splPrice, (int) $this->quantity);
    }
}
