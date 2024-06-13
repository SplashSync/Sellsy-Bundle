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

namespace Splash\Connectors\Sellsy\Models\Metadata\Item;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contact Main Information Fields
 */
trait MainTrait
{
    /**
     * Product's name.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("name"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "List")),
        SPL\Flags(listed: true),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Product's name"),
        SPL\Microdata("http://schema.org/Product", "name")
    ]
    public ?string $name = null;

    /**
     * Product's reference.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("reference"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Product's reference"),
        SPL\IsRequired,
        SPL\Flags(listed: true),
        SPL\Microdata("http://schema.org/Product", "model"),
    ]
    public string $reference = "";

    /**
     * Product's reference price excluding taxes.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("reference_price_taxes_exc"),
        JMS\Type("string"),
        SPL\Field(
            type: SPL_T_VARCHAR,
            desc: "Product's reference price excluding taxes",
            group: "read",
        ),
        SPL\Microdata("http://schema.org/Product", "price"),
    ]
    public string $referencePriceTaxesExc = "0.00";

    /**
     * Product's purchase price excluding taxes.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("purchase_amount"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Product's purchase price excluding taxes"),
        SPL\Microdata("http://schema.org/Product", ""),
        SPL\IsWriteOnly()
    ]
    public string $purchaseAmount = "0.00";

    /**
     * Product's reference price including taxes.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("reference_price_taxes_inc"),
        JMS\Groups(array("Read")),
        SPL\Field(
            type: SPL_T_VARCHAR,
            desc: "Product's reference price including taxes",
            group: "write"
        ),
        SPL\Microdata("http://schema.org/Product", ""),
    ]
    public string $referencePriceTaxesInc = "0.00";

    #[
        Assert\Type("boolean"),
        JMS\SerializedName("is_reference_price_taxes_free"),
        JMS\Type("boolean"),
        SPL\Field(type: SPL_T_BOOL, desc: "Product is reference price has taxes free"),
        SPL\Microdata("http://schema.org/Product", ""),
        SPL\IsWriteOnly()
    ]
    public bool $isReferencePriceTaxesFree = false;

    /**
     * Product's Currency code.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("currency"),
        JMS\Type("string"),
        JMS\Groups(array("Read")),
        SPL\Field(
            type: SPL_T_VARCHAR,
            desc: "Currency code",
            group: "write"
        ),
        SPL\Microdata("http://schema.org/Product", ""),
    ]
    public ?string $currency = null;

    /**
     * Product's Standard quantity.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("standard_quantity"),
        JMS\Type("string"),
        JMS\Groups(array("Write", "Read", "List")),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Product's standard quantity"),
        SPL\Microdata("http://schema.org/Product", "")
    ]
    public string $standardQuantity = "1.00";

    /**
     * Product's Description.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("description"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "List")),
        SPL\Field(type: SPL_T_TEXT, desc: "Product's description"),
        SPL\Microdata("http://schema.org/Product", "")
    ]
    public ?string $description = null;

    /**
     * Is the name of the product included in the desc.
     */
    #[
        Assert\Type("boolean"),
        JMS\SerializedName("is_name_included_in_description"),
        JMS\Type("boolean"),
        JMS\Groups(array("Read", "Write", "List")),
        SPL\Field(type: SPL_T_BOOL, desc: "To add the name of item in description"),
        SPL\Microdata("http://schema.org/Product", "")
    ]
    public bool $isNameInDescription = false;
}
