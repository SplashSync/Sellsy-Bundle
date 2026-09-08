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

use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sellsy Webhook Event
 */
class Event
{
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("id"),
        Serializer\Groups(array(SplGroups::REQUIRED, SplGroups::READ, SplGroups::WRITE)),
        SPL\Field(desc: "Event ID"),
    ]
    public string $id;

    #[
        Assert\Type("boolean"),
        Serializer\SerializedName("is_enabled"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(),
    ]
    public bool $isEnabled = true;

    #[
        Assert\Type("string"),
        Serializer\SerializedName("channel"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Field(),
        SPL\IsReadOnly(),
    ]
    public ?string $channel = null;
}
