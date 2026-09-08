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

use Splash\Connectors\Sellsy\Models\Metadata\Common\Discount;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Related;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Splash\Templates\Accounting\AccountingItemsFields;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

abstract class ProductRow extends AbstractRow
{
    /**
     * Row Reference.
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("reference"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(AccountingItemsFields::ITEM_PRODUCT_SKU),
        SPL\Associations(array("quantity@rows")),
    ]
    public ?string $reference = null;

    /**
     * Row Description.
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("description"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(AccountingItemsFields::ITEM_DESCRIPTION),
        SPL\Associations(array("quantity@rows")),
    ]
    public ?string $description = null;

    /**
     * Row unit price excluding taxes.
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("unit_amount"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(AccountingItemsFields::ITEM_UNIT_PRICE),
        SPL\Associations(array("quantity@rows")),
    ]
    public ?string $unitAmount = null;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("quantity"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(AccountingItemsFields::ITEM_QUANTITY),
    ]
    public string $quantity;

    /**
     * Discount.
     */
    #[
        Assert\Type(Discount::class),
        Serializer\SerializedName("discount"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE)),
        SPL\Template(AccountingItemsFields::ITEM_DISCOUNT),
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
        Serializer\SerializedName("tax_id"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(AccountingItemsFields::ITEM_VAT_CODE),
        SPL\IsNotTested,
    ]
    public int $taxId = 0;

    /**
     * Row Related Catalog Item.
     *
     * Only carried by catalog, shipping & packaging rows: on a free row it
     * stays null, and null values are never sent to Sellsy.
     */
    #[
        Assert\Type(Related::class),
        Serializer\SerializedName("related"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(AccountingItemsFields::ITEM_PRODUCT_ID),
        SPL\Associations(array("quantity@rows")),
    ]
    public ?Related $related = null;

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
