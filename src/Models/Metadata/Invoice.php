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

use Splash\Connectors\Sellsy\Models\Metadata\Common\RowsAwareTrait;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Attributes\Rest\RestResource;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Splash\Templates\InvoiceFields;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Api Metadata Model for Simple Object: Basic Fields.
 *
 * @SuppressWarnings(TooManyFields)
 */
#[RestResource(
    collectionUri: "/invoices",
    itemUri: "/invoices/{id}",
)]
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
    use Invoice\PaymentsTrait;
    use Invoice\MetadataTrait;
    use Invoice\LinksTraits;
    use Invoice\StatusTrait;
    use RowsAwareTrait;

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
        Serializer\SerializedName("number"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE, SplGroups::LIST)),
        SPL\Template(InvoiceFields::REF_INTERNAL),
        SPL\Flags(listed: true),
    ]
    public string $number;
}
