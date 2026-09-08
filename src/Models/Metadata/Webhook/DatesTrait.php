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
use Splash\Core\Dictionary\SplFields;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait DatesTrait
{
    #[
        Assert\Type("datetime"),
        Serializer\SerializedName("created"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Field(type: SplFields::DATETIME, desc: "Date Created", group: "Dates"),
        SPL\IsReadOnly,
    ]
    public DateTime $created;

    #[
        Assert\Type("datetime"),
        Serializer\SerializedName("updated"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Field(type: SplFields::DATETIME, desc: "Date Updated", group: "Dates"),
        SPL\IsReadOnly,
    ]
    public ?DateTime $updated = null;

    #[
        Assert\Type("datetime"),
        Serializer\SerializedName("last_succeeded"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Field(type: SplFields::DATETIME, desc: "Last Succeeded", group: "Dates"),
        SPL\IsReadOnly,
    ]
    public ?DateTime $lastSucceeded = null;

    #[
        Assert\Type("datetime"),
        Serializer\SerializedName("last_failed"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Field(type: SplFields::DATETIME, desc: "Last Failed", group: "Dates"),
        SPL\IsReadOnly,
    ]
    public ?DateTime $lastFailed = null;
}
