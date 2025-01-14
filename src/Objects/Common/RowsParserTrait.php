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

namespace Splash\Connectors\Sellsy\Objects\Common;

use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models\ProductRow;

trait RowsParserTrait
{
    /**
     * Read requested Field
     */
    protected function getProductRowsFields(string $key, string $fieldName): void
    {
        //====================================================================//
        // Check if List field & Init List Array
        $fieldId = self::lists()->initOutput($this->out, "rows", $fieldName);
        if (!$fieldId) {
            return;
        }
        unset($this->in[$key]);
        //====================================================================//
        // Verify List is Not Empty
        if (empty($rows = $this->object->getProductRows())) {
            return;
        }
        //====================================================================//
        // Fill List with Data
        foreach ($rows as $index => $row) {
            //====================================================================//
            // Read Data from Line Item
            $value = match ($fieldId) {
                "id" => $row->getId(),
                "rowType" => $row->getType(),
                "related" => $row->related?->toSplash(),
                "reference" => $row->reference,
                "description" => $row->description,
                "quantity" => (int) $row->quantity,
                "unitAmount" => $this->getSplashPrice($row),
                "taxId" => $this->connector->getLocator()->getTaxManager()->getLabel($row->taxId),
                "discount" => $row->getDiscount($this->getSplashPrice($row)),
                default => null,
            };
            //====================================================================//
            // Insert Data in List
            self::lists()->insert($this->out, "rows", $fieldName, $index, $value);
        }
    }

    /**
     * Write Given Fields
     *
     * @param string       $fieldName Field Identifier / Name
     * @param null|array[] $fieldData Field Data
     *
     * @return void
     */
    protected function setProductRowsFields(string $fieldName, ?array $fieldData): void
    {
        //====================================================================//
        // Safety Check
        if (("rows" !== $fieldName)) {
            return;
        }
        //====================================================================//
        // Update Rows using Rows Updater
        $updated = $this->connector
            ->getLocator()
            ->getRowsUpdater()
            ->update($this->object->rows, $fieldData ?? array())
        ;
        if ($updated) {
            $this->needUpdate();
        }

        unset($this->in[$fieldName]);
    }

    /**
     * Extract Product Row Price
     */
    private function getSplashPrice(ProductRow $row): ?array
    {
        return self::prices()->encode(
            (float) $row->unitAmount,
            $this->connector->getLocator()->getTaxManager()->getRate($row->taxId),
            null,
            $this->object->currency ?: $this->connector->getDefaultCurrency()
        );
    }
}
