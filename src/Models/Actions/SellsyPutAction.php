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

namespace Splash\Connectors\Sellsy\Models\Actions;

use Splash\Connectors\Sellsy\Dictionary\RowsKeys;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\CommentRow;
use Splash\OpenApi\Action\Json\PutAction;
use Splash\OpenApi\Interfaces\Visitor\VisitorInterface;

/**
 * Update Sellsy Objects with PUT Method
 *
 * Sales documents rows are polymorphic: Symfony always writes the class
 * discriminator, whereas Sellsy expects it only when the row is created.
 */
class SellsyPutAction extends PutAction
{
    /**
     * @inheritDoc
     */
    protected function extractData(VisitorInterface $visitor, object $object): array
    {
        //====================================================================//
        // Sellsy updates are partial: a field that is not sent is left
        // untouched, while sending null on it is refused by the Api.
        $data = self::withoutNulls(parent::extractData($visitor, $object));
        //====================================================================//
        // Safety Check - Document has Rows
        if (!is_array($rows = $data[RowsKeys::ROWS] ?? null)) {
            return $data;
        }
        //====================================================================//
        // Walk on Document Rows
        $cleaned = array();
        foreach ($rows as $row) {
            if (!is_array($row)) {
                $cleaned[] = $row;

                continue;
            }
            //====================================================================//
            // New rows are identified by their type & their catalog item,
            // updated ones only by their id: Sellsy refuses any extra key on
            // both shapes, except on comment rows, whose only shape always
            // carries the type.
            if (empty($row[RowsKeys::ID])) {
                unset($row[RowsKeys::ID]);
            } elseif (CommentRow::DATATYPE !== ($row[RowsKeys::TYPE] ?? null)) {
                unset($row[RowsKeys::TYPE], $row[RowsKeys::RELATED]);
            }
            $cleaned[] = $row;
        }
        $data[RowsKeys::ROWS] = $cleaned;

        return $data;
    }

    /**
     * Remove Null Values from a Write Payload
     */
    private static function withoutNulls(array $data): array
    {
        $filtered = array();
        foreach ($data as $key => $value) {
            if (null === $value) {
                continue;
            }
            $filtered[$key] = is_array($value) ? self::withoutNulls($value) : $value;
        }

        return array_is_list($data) ? array_values($filtered) : $filtered;
    }
}
