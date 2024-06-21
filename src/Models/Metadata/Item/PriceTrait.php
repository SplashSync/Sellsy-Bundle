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

trait PriceTrait
{
    /**
     * Product's reference price.
     */
    #[
        Assert\NotNull,
        Assert\Type("double"), // float ?
        JMS\SerializedName("reference_price"),
        JMS\Type("double"),
        JMS\Groups(array("Write")),
    ]
    public float $referencePrice = 0.0;

    /**
     * Product's reference price excluding taxes.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("reference_price_taxes_exc"),
        JMS\Type("string"),
        JMS\Groups(array("Read")),
        SPL\IsReadOnly,
        SPL\Field(
            type: SPL_T_VARCHAR,
            desc: "Product's reference price excluding taxes",
        )
    ]
    public string $referencePriceTaxesExc = "0.00";

    /**
     * Product's reference price including taxes.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("reference_price_taxes_inc"),
        JMS\Type("string"),
        JMS\Groups(array("Read")),
        SPL\IsReadOnly,
        SPL\Field(
            type: SPL_T_VARCHAR,
            desc: "Product's reference price including taxes",
        )
    ]
    public string $referencePriceTaxesInc = "0.00";

    #[
        Assert\Type("boolean"),
        JMS\SerializedName("is_reference_price_taxes_free"),
        JMS\Type("boolean"),
        SPL\Field(type: SPL_T_BOOL, desc: "Product is reference price has taxes free"),
        SPL\IsReadOnly
    ]
    public bool $isReferencePriceTaxesFree = false;

    /**
     * Product's tax id.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("tax_id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_INT, desc: "Tax id"),
        SPL\Microdata("http://schema.org/Product", "taxID")
    ]
    public int $taxId = 0;

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
        SPL\Flags(
            read: false,
            write: false
        ),
        SPL\Field(
            type: SPL_T_VARCHAR,
            desc: "Currency code",
        ),
        SPL\Microdata("http://schema.org/Product", ""),
    ]
    public ?string $currency = null;
}
