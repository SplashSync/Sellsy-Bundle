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

namespace Splash\Connectors\Sellsy\Services\Accounting;

use Psr\Cache\InvalidArgumentException;
use Splash\Connectors\Sellsy\Dictionary\RowTypes;
use Splash\Connectors\Sellsy\Interfaces\SellsyConnectorAwareInterface;
use Splash\Connectors\Sellsy\Models\Connector\SellsyConnectorAwareTrait;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\CatalogRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\CommentRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models\AbstractRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models\ProductRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Related;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\SingleRow;
use Splash\Core\Helpers\ObjectsHelper;
use Splash\Core\Helpers\PricesHelper;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Manage CRUD for Sellsy Orders/Invoices/More... Products Rows
 */
class RowsUpdater implements SellsyConnectorAwareInterface
{
    use SellsyConnectorAwareTrait;

    /**
     * Current Position in Rows List
     */
    private int $rowsCursor = -1;

    /**
     * Rows Updated
     */
    private bool $updated = false;

    public function __construct(
        private readonly CacheInterface $appCache,
    ) {
    }

    /**
     * Update Object Rows with received Data
     *
     * @param AbstractRow[] $rows
     * @param array[]       $rowsData
     *
     * @return bool True if Rows Updated
     */
    public function update(array &$rows, array $rowsData): bool
    {
        //====================================================================//
        // Reset Rows Cursor before Writing
        $this->reset();
        //====================================================================//
        // Verify Lines List & Update if Needed
        foreach ($rowsData as $rowData) {
            //====================================================================//
            // Fetch Next Syncable Row
            $row = $this->getNextSyncableRow($rows);
            //====================================================================//
            // Ensure Row is from Correct Type, or Create a new one
            $row = $this->updateRowClass($row, $rowData);
            $checksum = $row->getChecksum();
            //====================================================================//
            // Update Row Contents
            $this->updateScalarValues($row, $rowData);
            if ($row instanceof ProductRow) {
                $this
                    ->updatePrice($row, $rowData)
                    ->updateRelated($row, $rowData)
                    ->updateDiscount($row, $rowData)
                ;
            }
            //====================================================================//
            // Push updated Row to Rows
            $rows[$this->rowsCursor] = $row;
            if ($checksum != $row->getChecksum()) {
                $this->updated = true;
            }
        }
        //====================================================================//
        // Delete Remaining Lines
        while ($this->getNextSyncableRow($rows)) {
            unset($rows[$this->rowsCursor]);
            $this->updated = true;
        }

        return $this->updated;
    }

    /**
     * Get Next Row that Splash is able to write
     *
     * Layout rows are walked over: they keep their place in the document.
     *
     * @param AbstractRow[] $rows
     */
    public function getNextSyncableRow(array $rows): ?AbstractRow
    {
        foreach ($rows as $index => $row) {
            //====================================================================//
            // Check Cursor
            if ($index <= $this->rowsCursor) {
                continue;
            }
            //====================================================================//
            // This is a Row Splash may write
            if (($row instanceof ProductRow) || ($row instanceof CommentRow)) {
                $this->rowsCursor = $index;

                return $row;
            }
        }
        $this->rowsCursor++;

        return null;
    }

    /**
     * Check current Row if Suitable for Received Data
     */
    public function updateRowClass(?AbstractRow $row, array $rowData): AbstractRow
    {
        //====================================================================//
        // Comment Row: carries a text, and nothing else
        if (RowTypes::isComment($rowData["rowType"] ?? null)) {
            return ($row instanceof CommentRow) ? $row : new CommentRow();
        }
        //====================================================================//
        // Stored row was a comment, received data is not: start over
        $row = ($row instanceof ProductRow) ? $row : null;
        //====================================================================//
        // Related was not written: keep the stored row, so that its remote
        // id survives a partial write of the document rows.
        if ($row && !array_key_exists("related", $rowData)) {
            return $row;
        }

        return $this->updateProductRowClass($row, $rowData);
    }

    /**
     * Check current Product Row if Suitable for Received Data
     */
    public function updateProductRowClass(?ProductRow $row, array $rowData): ProductRow
    {
        //====================================================================//
        // Should be a Single Row
        if (empty($objectId = $rowData["related"] ?? null)) {
            return ($row instanceof SingleRow) ? $row : new SingleRow();
        }
        //====================================================================//
        // Should be a Catalog Row => Related Changed ?
        if (($row instanceof CatalogRow) && ($objectId == $row->related?->toSplash())) {
            return $row;
        }

        return $this->newProductRow((string) $objectId);
    }

    /**
     * Build the Row Class matching a Catalog Product
     */
    public function newProductRow(string $objectId): ProductRow
    {
        //====================================================================//
        // New product Not Found
        if (!$productData = $this->getProductInfos((string) ObjectsHelper::id($objectId))) {
            return new SingleRow();
        }

        //====================================================================//
        // New product Found
        return RowTypes::fromItemType($productData["type"] ?? null);
    }

    /**
     * Update Row Contents from received Splash Data
     */
    public function updateScalarValues(AbstractRow &$row, array $rowData): static
    {
        //====================================================================//
        // Comment Rows only store the received description
        if ($row instanceof CommentRow) {
            if (array_key_exists("description", $rowData)) {
                $row->text = (string) $rowData["description"];
            }

            return $this;
        }
        //====================================================================//
        // Safety Check - Other Rows are Product Rows
        if (!$row instanceof ProductRow) {
            return $this;
        }
        //====================================================================//
        // Update of Simple Contents
        if (array_key_exists("reference", $rowData)) {
            $row->reference = $rowData["reference"];
        }
        if (array_key_exists("description", $rowData)) {
            $row->description = $rowData["description"];
        }
        if (array_key_exists("quantity", $rowData)) {
            $row->quantity = sprintf("%.2f", $rowData["quantity"]);
        }

        return $this;
    }

    /**
     * Update Row Price & TaxId from received Splash Data
     */
    public function updatePrice(ProductRow &$row, array $rowData): static
    {
        $taxManager = $this->connector->getLocator()->getTaxManager();
        //====================================================================//
        // Update of Unit Price
        if (array_key_exists("unitAmount", $rowData)) {
            $unitAmount = PricesHelper::taxExcluded($rowData["unitAmount"]);
            $row->unitAmount = sprintf("%.2f", $unitAmount ?: 0.0);
        }
        //====================================================================//
        // Update of Tax ID
        if (array_key_exists("taxId", $rowData) && !empty($rowData["taxId"])) {
            if ($newTaxId = $taxManager->findByLabel((string) $rowData["taxId"])) {
                $row->taxId = $newTaxId;
            }
        } elseif (array_key_exists("unitAmount", $rowData)) {
            if ($newTaxId = $taxManager->findClosestTaxRate((float) PricesHelper::taxPercent($rowData["unitAmount"]))) {
                $row->taxId = $newTaxId;
            }
        }

        return $this;
    }

    /**
     * Update Row Discount from received Splash Data
     */
    public function updateDiscount(ProductRow &$row, array $rowData): static
    {
        if (!array_key_exists("discount", $rowData)) {
            return $this;
        }

        $splPrice = PricesHelper::encode(
            (float) $row->unitAmount,
            $this->connector->getLocator()->getTaxManager()->getRate($row->taxId),
            null,
            "EUR"
        );

        $row->setDiscount($rowData["discount"], $splPrice);

        return $this;
    }

    /**
     * Update Row Related from received Splash Data
     */
    public function updateRelated(ProductRow &$row, array $rowData): static
    {
        //====================================================================//
        // NO Update Required => Nothing received
        if (!array_key_exists("related", $rowData)) {
            return $this;
        }
        //====================================================================//
        // NO Update Required => NO Change
        $objectId = $rowData["related"] ?? null;
        if ($objectId == $row->related?->toSplash()) {
            return $this;
        }
        //====================================================================//
        // Empty Related
        if (empty($objectId)) {
            return $this;
        }
        //====================================================================//
        // Load New Product Informations with Caching
        $productData = $this->getProductInfos((string) ObjectsHelper::id($objectId));

        //====================================================================//
        // Update of Connected Product
        if ($productData) {
            //====================================================================//
            // Configure
            $row->rowType = in_array($productData["type"], array("shipping", "packaging"), true)
                ? $productData["type"]
                : "catalog"
            ;
            //====================================================================//
            // Configure Related
            $row->related ??= new Related();
            $row->related->fromSplash($objectId);
            $row->related->type = (string) $productData["type"];
            //====================================================================//
            // Configure Ref. & Description
            $row->reference ??= $productData["reference"];
            $row->description ??= $productData["description"];
        }

        return $this;
    }

    /**
     * Get Product Information from Product ID
     */
    public function getProductInfos(string $productId): ?array
    {
        //====================================================================//
        // Build cache key
        $cacheKey = sprintf("Sellsy-%s-Product-%s", $this->connector->getWebserviceId(), md5($productId));

        //====================================================================//
        // Load Product Informations with Caching
        try {
            $productInfo = $this->appCache->get($cacheKey, function (ItemInterface $item) use ($productId): ?array {
                //====================================================================//
                // Setup a Short Cache Storage
                $item->expiresAfter(10);

                //====================================================================//
                // Load Product Informations from API
                return $this->connector->getObject(
                    "Product",
                    $productId,
                    array("type", "reference", "description")
                );
            });

            return is_array($productInfo) ? $productInfo : null;
        } catch (InvalidArgumentException) {
            return null;
        }
    }

    /**
     * Reset Parser Cursor (Index)
     */
    private function reset(): void
    {
        $this->rowsCursor = -1;
        $this->updated = false;
    }
}
