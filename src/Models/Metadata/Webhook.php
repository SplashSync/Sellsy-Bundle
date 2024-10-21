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
use Splash\Connectors\Sellsy\Models\Metadata\Webhook\Event;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Api Metadata Model for Sellsy Webhook.
 */
#[SPL\SplashObject(
    name: "Webhook",
    description: "Sellsy Webhook API Object",
    ico: "fa fa-bolt"
)]
class Webhook
{
    use Webhook\DatesTrait;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("id"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
    ]
    public string $id;

    #[
        Assert\Type("boolean"),
        JMS\SerializedName("is_enabled"),
        JMS\Type("boolean"),
        JMS\Groups(array("Required", "Read", "Write", "List")),
        SPL\Field(desc: "Enabled"),
        SPL\Flags(listed: true),
    ]
    public bool $is_enabled = true;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("type"),
        JMS\Type("string"),
        JMS\Groups(array("Required", "Read", "List")),
        SPL\Field(desc: "Webhook Type"),
        SPL\Flags(listed: true),
        SPL\IsReadOnly(),
    ]
    public string $type = "http";

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("endpoint"),
        JMS\Type("string"),
        JMS\Groups(array("Required", "Read", "Write", "List")),
        SPL\Field(desc: "Endpoint"),
        SPL\Flags(listed: true, required: true),
    ]
    public string $endpoint = "https://exemple.sellsy.com";

    #[
        Assert\Type("string"),
        JMS\SerializedName("default_channel"),
        JMS\Type("string"),
        JMS\Groups(array("Required", "Read", "Write", "List")),
        SPL\Field(name: "Channel", desc: "Channel"),
        SPL\Flags(listed: true, required: true),
    ]
    public ?string $default_channel = null;

    #[
        Assert\Type("array"),
        JMS\SerializedName("configuration"),
        JMS\Groups(array("Required", "Read", "Write")),
        JMS\Type("array<".Event::class.">"),
        SPL\ListResource(targetClass: Event::class),
        SPL\IsRequired,
    ]
    public array $configuration;
}
