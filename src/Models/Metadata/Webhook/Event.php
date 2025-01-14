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

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sellsy Webhook Event
 */
class Event
{
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("id"),
        JMS\Type("string"),
        JMS\Groups(array("Required", "Read", "Write")),
        SPL\Field(desc: "Event ID"),
    ]
    public string $id;

    #[
        Assert\Type("boolean"),
        JMS\SerializedName("is_enabled"),
        JMS\Type("boolean"),
        SPL\Field(),
    ]
    public bool $isEnabled = true;

    #[
        Assert\Type("string"),
        JMS\SerializedName("channel"),
        JMS\Type("string"),
        JMS\Groups(array("Read")),
        SPL\Field(),
        SPL\IsReadOnly(),
    ]
    public ?string $channel = null;
}
