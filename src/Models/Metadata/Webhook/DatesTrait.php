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

namespace Splash\Connectors\Sellsy\Models\Metadata\Webhook;

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

trait DatesTrait
{
    #[
        Assert\Type("datetime"),
        JMS\SerializedName("created"),
        JMS\Type("DateTime"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_DATETIME, desc: "Date Created", group: "Dates"),
        SPL\IsReadOnly,
    ]
    public DateTime $created;

    #[
        Assert\Type("datetime"),
        JMS\SerializedName("updated"),
        JMS\Type("DateTime"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_DATETIME, desc: "Date Updated", group: "Dates"),
        SPL\IsReadOnly,
    ]
    public ?DateTime $updated = null;

    #[
        Assert\Type("datetime"),
        JMS\SerializedName("last_succeeded"),
        JMS\Type("DateTime"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_DATETIME, desc: "Last Succeeded", group: "Dates"),
        SPL\IsReadOnly,
    ]
    public ?DateTime $lastSucceeded = null;

    #[
        Assert\Type("datetime"),
        JMS\SerializedName("last_failed"),
        JMS\Type("DateTime"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_DATETIME, desc: "Last Failed", group: "Dates"),
        SPL\IsReadOnly,
    ]
    public ?DateTime $lastFailed = null;
}
