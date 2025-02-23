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

namespace Splash\Connectors\Sellsy\Models\Metadata\Contact;

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contact Metadata Fields
 */
trait MetadataTrait
{
    #[
        Assert\Type("boolean"),
        JMS\SerializedName("is_archived"),
        JMS\Type("boolean"),
        SPL\Field(type: SPL_T_BOOL, desc: "Is Contact Archived", group: "Meta"),
    ]
    public bool $isArchived;

    #[
        Assert\Type("datetime"),
        JMS\SerializedName("created"),
        JMS\Type("DateTime"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_DATETIME, desc: "Creation date", group: "Meta"),
        SPL\IsReadOnly,

    ]
    public DateTime $created;

    #[
        Assert\Type("datetime"),
        JMS\SerializedName("updated"),
        JMS\Type("DateTime"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_DATETIME, desc: "Last Update Date", group: "Meta"),
        SPL\IsReadOnly,
    ]
    public DateTime $updated;
}
