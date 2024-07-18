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
     * Product's purchase price excluding taxes.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("purchase_amount"),
        JMS\Type("string"),
    ]
    public string $purchaseAmount = "0.00";

    /**
     * Product's Standard quantity.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("standard_quantity"),
        JMS\Type("string"),
        JMS\Groups(array("Write", "Read", "List")),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Product's standard quantity"),
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
        SPL\Field(type: SPL_T_VARCHAR, desc: "Product's description"),
        SPL\Microdata("http://schema.org/Product", "description")
    ]
    public ?string $description = "";

    /**
     * Is the name of the product included in the desc.
     */
    #[
        Assert\Type("boolean"),
        JMS\SerializedName("is_name_included_in_description"),
        JMS\Type("boolean"),
        JMS\Groups(array("Read", "Write", "List")),
        SPL\Field(type: SPL_T_BOOL, desc: "To add the name of item in description"),
    ]
    public bool $isNameInDescription = false;
}
