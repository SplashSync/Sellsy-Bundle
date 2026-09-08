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

use Splash\Connectors\Sellsy\Models\Metadata\Webhook\Event;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Attributes\Rest\RestResource;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Api Metadata Model for Sellsy Webhook.
 *
 * @SuppressWarnings(CamelCasePropertyName)
 */
#[RestResource(
    // Webhooks are created & listed on the plain collection: Sellsy has no
    // search endpoint for them, the List Action asks for none.
    collectionUri: "/webhooks",
    itemUri: "/webhooks/{id}",
)]
#[SPL\SplashObject(
    name: "Webhook",
    description: "Sellsy Webhook API Object",
    ico: "fa fa-bolt"
)]
class Webhook
{
    use Webhook\DatesTrait;

    #[
        Assert\Type("string"),
        Serializer\SerializedName("id"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::LIST)),
    ]
    //====================================================================//
    // Nullable on purpose: an object being created has no id yet, and the
    // Visitor reads this property to identify it.
    public ?string $id = null;

    #[
        Assert\Type("boolean"),
        Serializer\SerializedName("is_enabled"),
        Serializer\Groups(array(SplGroups::REQUIRED, SplGroups::READ, SplGroups::WRITE, SplGroups::LIST)),
        SPL\Field(desc: "Enabled"),
        SPL\Flags(listed: true),
    ]
    public bool $is_enabled = true;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("type"),
        Serializer\Groups(array(SplGroups::REQUIRED, SplGroups::READ, SplGroups::LIST)),
        SPL\Field(desc: "Webhook Type"),
        SPL\Flags(listed: true),
        SPL\IsReadOnly(),
    ]
    public string $type = "http";

    #[
        Assert\Type("string"),
        Serializer\SerializedName("name"),
        Serializer\Groups(array(SplGroups::REQUIRED, SplGroups::READ, SplGroups::WRITE, SplGroups::LIST)),
        SPL\Field(desc: "Webhook Name"),
        SPL\Flags(listed: true),
    ]
    public ?string $name = null;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("endpoint"),
        Serializer\Groups(array(SplGroups::REQUIRED, SplGroups::READ, SplGroups::WRITE, SplGroups::LIST)),
        SPL\Field(desc: "Endpoint"),
        SPL\Flags(listed: true, required: true),
    ]
    public string $endpoint = "https://exemple.sellsy.com";

    #[
        Assert\Type("string"),
        Serializer\SerializedName("default_channel"),
        Serializer\Groups(array(SplGroups::REQUIRED, SplGroups::READ, SplGroups::WRITE, SplGroups::LIST)),
        SPL\Field(name: "Channel", desc: "Channel"),
        SPL\Flags(listed: true, required: true),
    ]
    public ?string $default_channel = null;

    /**
     * @var Event[]
     */
    #[
        Assert\Type("array"),
        Serializer\SerializedName("configuration"),
        Serializer\Groups(array(SplGroups::REQUIRED, SplGroups::READ, SplGroups::WRITE)),
        SPL\ListResource(targetClass: Event::class),
        SPL\IsRequired,
    ]
    public array $configuration;
}
