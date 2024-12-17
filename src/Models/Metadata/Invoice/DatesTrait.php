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

trait DatesTrait
{
    /**
     * Invoice's date.
     */
    #[
        Assert\NotNull,
        Assert\Type("date"),
        JMS\SerializedName("date"),
        JMS\Type("DateTime<'Y-m-d'>"),
        JMS\Groups(array("Read", "Write", "Required")),
        SPL\Field(type: SPL_T_DATE, desc: "Date of the invoice"),
        SPL\IsRequired,
    ]
    public \DateTime $date;

    //    /**
    //     * Invoice's shipping date.
    //     */
    //    #[
    //        Assert\Type("string<string>"),
    //        JMS\SerializedName("shipping_date"),
    //        JMS\Type("string<date>"),
    //        SPL\Field(type: SPL_T_DATE, desc: "Shipping Date of the invoice"),
    //    ]
    //    public ?string $shippingDate = null;
    //
    //    /**
    //     * Invoice's due date.
    //     */
    //    #[
    //        Assert\NotNull,
    //        Assert\Type("date"),
    //        JMS\SerializedName("due_date"),
    //        JMS\Type("string<date>"),
    //        SPL\Field(type: SPL_T_DATE, desc: "Due Date of the invoice"),
    //    ]
    //    public string $dueDate = "";
}
