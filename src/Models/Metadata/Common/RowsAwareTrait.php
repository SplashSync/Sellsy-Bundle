<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Common;

use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models\AbstractRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models\ProductRow;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

trait RowsAwareTrait
{
    /**
     * @var AbstractRow[]
     */
    #[
        Assert\Type("array"),
        JMS\SerializedName("rows"),
        JMS\Type("array<".AbstractRow::class.">"),
        SPL\ListResource(targetClass: ProductRow::class),
    ]
    public array $rows = array();

    /**
     * @return ProductRow[]
     */
    public function getRows(): array
    {
        return array_filter($this->rows, static function (AbstractRow $row) {
            return $row instanceof ProductRow;
        });
    }

}