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

/**
 * Products Extras Fields: ...
 */
trait ExtraInfosTrait
{
    /**
     * Product's unit id.
     */
    #[
        Assert\Type("integer"),
        Serializer\SerializedName("unit_id"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Field(type: SplFields::INT, desc: "Unit id", group: "Meta"),
        SPL\IsReadOnly(),
    ]
    public ?int $unitId = null;

    /**
     * Product's category id.
     */
    #[
        Assert\Type("integer"),
        Serializer\SerializedName("category_id"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Field(type: SplFields::INT, desc: "Category id", group: "Meta"),
        SPL\IsReadOnly(),
    ]
    public ?int $categoryId = null;

    /**
     * Product's accounting code id.
     */
    #[
        Assert\Type("integer"),
        Serializer\SerializedName("accounting_code_id"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Field(type: SplFields::VARCHAR, desc: "Product's accounting code id", group: "Meta"),
        SPL\IsReadOnly()
    ]
    public ?int $accountingCodeId = null;

    /**
     * Product's accounting purchase code id.
     */
    #[
        Assert\Type("integer"),
        Serializer\SerializedName("accounting_purchase_code_id"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Field(type: SplFields::VARCHAR, desc: "Product's accounting purchase code id", group: "Meta"),
        SPL\IsReadOnly()
    ]
    public ?int $accountingPurchaseCodeId = null;
}
