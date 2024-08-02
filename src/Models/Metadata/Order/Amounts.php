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

class Amounts
{
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total_raw_excl_tax"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "[Amounts] Total amount without taxes and discounts."),
        //        SPL\Microdata("http://schema.org/", "")
    ]
    public string $totalRawExclTax;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total_after_discount_excl_tax"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "[Amounts] Total discounted without tax."),
        //        SPL\Microdata("http://schema.org/", "")
    ]
    public string $totalAfterDiscountExclTax;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total_packaging"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "[Amounts] Total amount of packaging costs."),
        //        SPL\Microdata("http://schema.org/", "")
    ]
    public string $totalPackaging;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total_shipping"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "[Amounts] Total amount of shipping costs."),
        //        SPL\Microdata("http://schema.org/", "")
    ]
    public string $totalShipping;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total_excl_tax"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "[Amounts] Total net without tax."),
        //        SPL\Microdata("http://schema.org/", "")
    ]
    public string $totalExclTax;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total_incl_tax"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "[Amounts] Total with tax."),
        //        SPL\Microdata("http://schema.org/", "")
    ]
    public string $totalInclTax;
}
