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
use Splash\Connectors\Sellsy\Models\Metadata\Common\RowsAwareTrait;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Api Metadata Model for Simple Object: Basic Fields.
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
#[SPL\SplashObject(
    name: "Invoice",
    description: "Sellsy Invoice API Object",
    ico: "fa fa-user"
)]
class Invoice
{
    use Invoice\DatesTrait;
    use Invoice\MainTrait;
    use Invoice\RelationsTrait;
    //    use Invoice\ExtraInfosTrait;
    use Invoice\MetadataTrait;
    use Invoice\LinksTraits;
    use RowsAwareTrait;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("id"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
    ]
    public string $id;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("number"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(desc: "Invoice Number"),
        SPL\Flags(listed: true),
    ]
    public string $number;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("status"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "List")),
        SPL\Field(desc: "Third Party Id"),
        SPL\Flags(listed: true),
        SPL\Choices(array(
            "draft" => "Draft",
            "due" => "Due",
            "payinprogress" => "Pay In Progress",
            "paid" => "Paid",
            "late" => "Late",
            "canceled" => "Canceled",
        )),
    ]
    public string $status;
}
