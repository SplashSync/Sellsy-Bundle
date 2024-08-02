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

namespace Splash\Connectors\Sellsy\Models\Metadata\Order;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class Discount
{
    #[
        Assert\Type("string"),
        JMS\SerializedName("percent"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "[Discount] Percentage of the discount."),
        //        SPL\Microdata("http://schema.org/", "")
    ]
    public string $percent;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("amount"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "[Discount] Amount of the discount."),
        //        SPL\Microdata("http://schema.org/", "")
    ]
    public string $amount;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("type"),
        JMS\Type("string"),
        SPL\Field(
            type: SPL_T_VARCHAR,
            desc: "[Discount] Type of the global discount as defined on the document."
        ),
        SPL\Choices(array(
            "amount" => "Amount",
            "percent" => "Percent",
        )),
        //        SPL\Microdata("http://schema.org/", "")
    ]
    public string $type;
}
