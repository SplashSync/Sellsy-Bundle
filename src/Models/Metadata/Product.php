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

namespace Splash\Connectors\Sellsy\Models\Metadata;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

#[SPL\SplashObject(
    name: "Product",
    description: "Sellsy Products Object",
    ico: "fa fa-cube",
)]
class Product
{
    use Product\MainTrait;
    use Product\ExtraInfosTrait;
    use Product\MetadataTrait;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("id"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Microdata("http://schema.org/Product", "productID")
    ]
    public string $id;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("type"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "List", "Required")),
        SPL\Field(desc: "Item type"),
        SPL\Flags(listed: true),
        SPL\Choices(array(
            "product" => "Product",
            "service" => "Service",
            "shipping" => "Shipping",
            "packaging" => "Packaging"
        )),
        SPL\IsRequired,
    ]
    public string $type;
}
