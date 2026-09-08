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

use Splash\Core\Dictionary\SplFields;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait PriceTrait
{
    /**
     * Product's reference price.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("reference_price"),
        Serializer\Groups(array(SplGroups::WRITE)),
    ]
    public string $referencePrice = "0.0";

    /**
     * Product's reference price excluding taxes.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("reference_price_taxes_exc"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Field(
            type: SplFields::VARCHAR,
            desc: "Product's reference price excluding taxes",
            group: "Pricing",
        ),
        SPL\IsReadOnly,
    ]
    public string $referencePriceTaxesExc = "0.00";

    /**
     * Product's reference price including taxes.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("reference_price_taxes_inc"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Field(
            type: SplFields::VARCHAR,
            desc: "Product's reference price including taxes",
            group: "Pricing"
        ),
        SPL\IsReadOnly,
    ]
    public string $referencePriceTaxesInc = "0.00";

    #[
        Assert\Type("boolean"),
        Serializer\SerializedName("is_reference_price_taxes_free"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(
            type: SplFields::BOOL,
            desc: "Product is reference price has taxes free",
            group: "Pricing"
        ),
        SPL\IsReadOnly
    ]
    public bool $isReferencePriceTaxesFree = false;

    /**
     * Product's tax id.
     */
    #[
        Assert\Type("integer"),
        Serializer\SerializedName("tax_id"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::INT, desc: "Tax ID", group: "Meta"),
        SPL\IsReadOnly,
    ]
    public ?int $taxId = null;

    /**
     * Product's Currency code.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("currency"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Field(
            type: SplFields::CURRENCY,
            desc: "Currency code",
            group: "Pricing",
        ),
        SPL\IsReadOnly,
    ]
    public ?string $currency = null;
}
