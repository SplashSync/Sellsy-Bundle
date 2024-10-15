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

namespace Splash\Connectors\Sellsy\Models\Metadata\Common;

use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\CatalogRow;
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
        SPL\ListResource(targetClass: CatalogRow::class),
        SPL\Manual,
    ]
    public array $rows = array();

    /**
     * @return ProductRow[]
     */
    public function getProductRows(): array
    {
        //        dump($this->rows);

        return array_filter($this->rows, static function (AbstractRow $row) {
            return $row instanceof ProductRow;
        });
    }
}