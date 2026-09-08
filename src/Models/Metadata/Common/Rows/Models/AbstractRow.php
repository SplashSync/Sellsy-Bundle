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

namespace Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models;

use Splash\Connectors\Sellsy\Dictionary\RowTypes;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\BreakLineRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\BreakPageRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\CatalogRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\CommentRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\PackagingRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\ShippingRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\SingleRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\SubTotalRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\TitleRow;
use Splash\Core\Dictionary\SplFields;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Splash\Templates\Accounting\AccountingItemsFields;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[Serializer\DiscriminatorMap(
    typeProperty: "type",
    mapping: array(
        // Mapped by Splash
        SingleRow::DATATYPE => SingleRow::class,
        CatalogRow::DATATYPE => CatalogRow::class,
        ShippingRow::DATATYPE => ShippingRow::class,
        PackagingRow::DATATYPE => PackagingRow::class,
        // NOT Mapped by Splash
        TitleRow::DATATYPE => TitleRow::class,
        CommentRow::DATATYPE => CommentRow::class,
        SubTotalRow::DATATYPE => SubTotalRow::class,
        BreakLineRow::DATATYPE => BreakLineRow::class,
        BreakPageRow::DATATYPE => BreakPageRow::class,
    )
)]
abstract class AbstractRow implements RowInterface
{
    #[
        Assert\NotNull,
        Assert\Type("integer"),
        Serializer\SerializedName("id"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::INT, desc: "Item ID"),
        SPL\IsReadOnly(),
    ]
    public int $id;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("type"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Template(AccountingItemsFields::ITEM_TYPE),
        SPL\IsNotTested(),
    ]
    public string $rowType;

    /**
     * @inheritdoc
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getType(): ?string
    {
        return $this->rowType ?? static::DATATYPE;
    }

    /**
     * Compute Row Checksum to Detect Changes
     */
    public function getChecksum(): string
    {
        return md5(serialize($this));
    }

    /**
     * Get Row Type as a Splash Normalized Line Type
     *
     * Null for the Sellsy layout rows, which Splash does not qualify.
     */
    public function getSplashType(): ?string
    {
        return RowTypes::toSplash($this->getType());
    }
}
