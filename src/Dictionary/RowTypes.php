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

namespace Splash\Connectors\Sellsy\Dictionary;

use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows;
use Splash\Core\Dictionary\Objects\Accounting\AccountLineType;

/**
 * Sales Documents Rows Types Dictionary
 *
 * Sellsy splits its documents in nine kinds of rows, where Splash only
 * qualifies what a row IS for accounting. Five of them have an equivalent,
 * the four layout ones have none: see self::LAYOUT.
 */
class RowTypes
{
    /**
     * Sellsy Rows Types, mapped to their Splash Normalized Type
     */
    const MAP = array(
        //====================================================================//
        // Sold Items
        Rows\CatalogRow::DATATYPE => AccountLineType::PRODUCT,
        Rows\SingleRow::DATATYPE => AccountLineType::PRODUCT,
        //====================================================================//
        // Document Charges
        Rows\ShippingRow::DATATYPE => AccountLineType::SHIPPING,
        Rows\PackagingRow::DATATYPE => AccountLineType::FEE,
        //====================================================================//
        // Free Text
        Rows\CommentRow::DATATYPE => AccountLineType::COMMENT,
    );

    /**
     * Sellsy Layout Rows Types
     *
     * Pure presentation, with no Splash equivalent: a document carrying any
     * of them was built by hand in Sellsy, and must be left to its owner.
     */
    const LAYOUT = array(
        Rows\TitleRow::DATATYPE,
        Rows\SubTotalRow::DATATYPE,
        Rows\BreakLineRow::DATATYPE,
        Rows\BreakPageRow::DATATYPE,
    );

    /**
     * Convert a Sellsy Row Type to its Splash Normalized Type
     */
    public static function toSplash(?string $rowType): ?string
    {
        return self::MAP[$rowType] ?? null;
    }

    /**
     * Check if a Sellsy Row Type has a Splash Equivalent
     */
    public static function isSyncable(?string $rowType): bool
    {
        return isset(self::MAP[$rowType]);
    }

    /**
     * Check if received Splash Line Type is a Comment
     */
    public static function isComment(?string $lineType): bool
    {
        return AccountLineType::isComment($lineType);
    }

    /**
     * Build the Row Class that matches a Catalog Item Type
     */
    public static function fromItemType(?string $itemType): Rows\Models\ProductRow
    {
        return match ($itemType) {
            ItemTypes::SHIPPING => new Rows\ShippingRow(),
            ItemTypes::PACKAGING => new Rows\PackagingRow(),
            default => new Rows\CatalogRow(),
        };
    }
}
