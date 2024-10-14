<?php

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
        if (!is_array($rows = $this->object->getProductRows())) {
            return;
        }
        //====================================================================//
        // Fill List with Data
        foreach ($rows as $index => $row) {
            //====================================================================//
            // Read Data from Line Item
            $value = match($fieldId) {
                "id" => $row->getId(),
                "rowType" => $row->getType(),
                "related" => $row->related?->toSplash(),
                "reference" => $row->reference,
                "description" => $row->description,
                "quantity" => (int) $row->quantity,
                "unitAmount" => $this->getSplashPrice($row),
                "taxId" => $this->connector->getLocator()->getTaxManager()->getLabel($row->taxId),
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
        $this->connector
            ->getLocator()
            ->getRowsUpdater()
            ->update($this->object->rows, $fieldData ?? array())
        ;

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