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

use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Attributes\Rest\RestResource;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Api Metadata Model for Simple Object: Basic Fields.
 *
 * @SuppressWarnings(TooManyFields)
 */
#[RestResource(
    collectionUri: "/companies",
    itemUri: "/companies/{id}".Company\CompanyEmbed::URI_QUERY,
)]
#[SPL\SplashObject(
    name: "Company",
    description: "Sellsy Company API Object",
    ico: "fa fa-user"
)]
class Company
{
    use Company\FullNameTrait;
    use Company\MainTrait;
    use Company\ExtraInfosTrait;
    use Company\EmbedTrait;
    use Company\AddressesTrait;
    use Company\MetadataTrait;

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
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("type"),
        // Type is writable: Splash declares it so, and Sellsy PUT needs it back
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE, SplGroups::LIST, SplGroups::REQUIRED)),
        SPL\Field(desc: "Company type"),
        SPL\Flags(listed: true),
        SPL\Choices(array(
            "prospect" => "Prospect",
            "client" => "Client",
            "supplier" => "Supplier",
        )),
        SPL\IsNotTested,
    ]
    public ?string $type = "client";
}
