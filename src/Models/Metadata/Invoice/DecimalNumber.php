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

namespace Splash\Connectors\Sellsy\Models\Metadata\Invoice;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class DecimalNumber
{
    /**
     * Decimal Number Value for Unit Price
     *
     * @var null|int
     */
    #[
        Assert\NotNull,
        Assert\Type("int"),
        JMS\SerializedName("unit_price"),
        JMS\Type("int"),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Decimal Number Value for Unit Price"),
    ]
    public ?int $unitPrice = null;

    /**
     * Decimal Number Value for Quantity
     *
     * @var null|int
     */
    #[
        Assert\NotNull,
        Assert\Type("int"),
        JMS\SerializedName("quantity"),
        JMS\Type("int"),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Decimal Number Value for Quantity"),
    ]
    public ?int $quantity = null;

    /**
     * Main precision of estimate
     *
     * @var int
     */
    #[
        Assert\NotNull,
        Assert\Type("int"),
        JMS\SerializedName("main"),
        JMS\Type("int"),
        SPL\Field(type: SPL_T_INT, desc: "Main precision of estimate"),
    ]
    public int $main = 0;
}
