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
use Splash\Templates\ProductFields;
use Symfony\Component\Serializer\Attribute as Serializer;
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
        Serializer\SerializedName("name"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE, SplGroups::LIST)),
        SPL\Template(ProductFields::NAME),
        SPL\Flags(listed: true),
    ]
    public ?string $name = null;

    /**
     * Product's reference.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("reference"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE, SplGroups::LIST, SplGroups::REQUIRED)),
        SPL\Template(ProductFields::SKU),
        SPL\IsRequired,
        SPL\Flags(listed: true),
    ]
    public string $reference = "";

    /**
     * Product's purchase price excluding taxes.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("purchase_amount"),
        Serializer\Groups(SplGroups::DEFAULT),
    ]
    public string $purchaseAmount = "0.00";

    /**
     * Product's Standard quantity.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("standard_quantity"),
        Serializer\Groups(array(SplGroups::WRITE, SplGroups::READ, SplGroups::LIST)),
        SPL\Field(type: SplFields::DOUBLE, desc: "Product's standard quantity"),
    ]
    public string $standardQuantity = "1.00";

    /**
     * Product's Description.
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("description"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE, SplGroups::LIST)),
        SPL\Template(ProductFields::SHORT_DESCRIPTION),
    ]
    public ?string $description = "";

    /**
     * Is the name of the product included in the desc.
     */
    #[
        Assert\Type("boolean"),
        Serializer\SerializedName("is_name_included_in_description"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE, SplGroups::LIST)),
        SPL\Field(type: SplFields::BOOL, desc: "To add the name of item in description"),
    ]
    public bool $isNameInDescription = false;
}
