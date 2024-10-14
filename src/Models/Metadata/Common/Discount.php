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

namespace Splash\Connectors\Sellsy\Models\Metadata\Common;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class Discount
{
    /**
     * Discount percentage
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("percent"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Discount Percentage"),
    ]
    public string $percent = "";

    /**
     * Discount amount
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("amount"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Discount Amount"),
    ]
    public string $amount = "";

    /**
     * Discount type
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("type"),
        JMS\Type("string"),
        SPL\Field(desc: "Discount Type"),
        SPL\Choices(array(
            "percent" => "Percent",
            "amount" => "Amount",
        ))
    ]
    public string $type = "";
}
