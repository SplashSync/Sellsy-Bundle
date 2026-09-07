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

use Splash\Connectors\Sellsy\Dictionary\EmbedQueries;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Attributes\Rest\RestResource;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[RestResource(
    collectionUri: "/contacts",
    itemUri: "/contacts/{id}".EmbedQueries::ADDRESSES,
)]
#[SPL\SplashObject(
    name: "Contact",
    description: "Sellsy Contacts Object",
    ico: "fa fa-envelope-o"
)]
class Contact
{
    use Contact\MainTrait;
    use Contact\ExtraInfosTrait;
    use Contact\EmbedTrait;
    use Contact\AddressesTrait;
    use Contact\MetadataTrait;
    use Contact\CompaniesLinkTrait;

    #[
        Assert\Type("string"),
        Serializer\SerializedName("id"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::LIST)),
    ]
    //====================================================================//
    // Nullable on purpose: an object being created has no id yet, and the
    // Visitor reads this property to identify it.
    public ?string $id = null;
}
