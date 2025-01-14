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

use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\BreakLineRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\BreakPageRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\CatalogRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\CommentRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\PackagingRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\ShippingRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\SingleRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\SubTotalRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\TitleRow;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

#[JMS\Discriminator(
    field: "type",
    map: array(
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
        JMS\SerializedName("id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_INT, desc: "Item ID"),
        SPL\IsReadOnly(),
    ]
    public int $id;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("type"),
        JMS\Groups(array("Read")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Item Type"),
        SPL\IsReadOnly(),
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
}
