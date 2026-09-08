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

use Splash\Connectors\Sellsy\Dictionary\RowTypes;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\CatalogRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models\AbstractRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models\ProductRow;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait RowsAwareTrait
{
    /**
     * @var AbstractRow[]
     */
    #[
        Assert\Type("array"),
        Serializer\SerializedName("rows"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\ListResource(targetClass: CatalogRow::class),
        SPL\Manual,
    ]
    public array $rows = array();

    /**
     * Check if Document carries Rows that Splash cannot represent
     *
     * Titles, sub-totals and page breaks are laid out by hand in Sellsy:
     * such a document belongs to its user, Splash must not rewrite it.
     */
    public function hasLayoutRows(): bool
    {
        foreach ($this->rows as $row) {
            if (!RowTypes::isSyncable($row->getType())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get Rows that Splash is able to represent
     *
     * Sold items & charges, plus the Sellsy comments, which Splash exposes
     * as comment lines. Original keys are kept: the writer walks them.
     *
     * @return AbstractRow[]
     */
    public function getSyncableRows(): array
    {
        return array_filter($this->rows, static function (AbstractRow $row) {
            return RowTypes::isSyncable($row->getType());
        });
    }

    /**
     * @return ProductRow[]
     */
    public function getProductRows(): array
    {
        return array_filter($this->rows, static function (AbstractRow $row) {
            return $row instanceof ProductRow;
        });
    }
}
