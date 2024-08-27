<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models;

use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\BreakLineRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\BreakPageRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\CatalogRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\CommentRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\PackagingRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\ShippingPackagingRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\ShippingRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\SubTotalRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\TitleRow;
use Symfony\Component\Validator\Constraints as Assert;
use Splash\Metadata\Attributes as SPL;

#[JMS\Discriminator(
    field: "type",
    map: array(

        CatalogRow::DATATYPE => CatalogRow::class,
        TitleRow::DATATYPE => TitleRow::class,
        CommentRow::DATATYPE => CommentRow::class,
        SubTotalRow::DATATYPE => SubTotalRow::class,
        BreakLineRow::DATATYPE => BreakLineRow::class,
        BreakPageRow::DATATYPE => BreakPageRow::class,
        ShippingRow::DATATYPE => ShippingRow::class,
        PackagingRow::DATATYPE => PackagingRow::class,
    )
)]
abstract class AbstractRow implements RowInterface
{
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("id"),
        JMS\Groups(array("Read", "Write")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_INT, desc: "Item ID"),
        SPL\IsReadOnly(),
    ]
    public string $id;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("type"),
        JMS\Groups(array("Read")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Item Type"),
        SPL\IsReadOnly(),
    ]
    public readonly string $rowType;

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