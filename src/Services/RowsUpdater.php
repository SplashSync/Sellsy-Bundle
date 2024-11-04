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

namespace Splash\Connectors\Sellsy\Services;

use Psr\Cache\InvalidArgumentException;
use Splash\Connectors\Sellsy\Models\Connector\SellsyConnectorAwareTrait;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\CatalogRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models\AbstractRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models\ProductRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\PackagingRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Related;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\ShippingRow;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\SingleRow;
use Splash\Models\Helpers\ObjectsHelper;
use Splash\Models\Helpers\PricesHelper;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Manage CRUD for Sellsy Orders/Invoices/More... Products Rows
 */
class RowsUpdater
{
    use SellsyConnectorAwareTrait;

    /**
     * Current Position in Rows List
     */
    private int $rowsCursor = -1;

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
     * @return AbstractRow[]
     */
    public function update(array &$rows, array $rowsData): array
    {
        //====================================================================//
        // Reset Rows Cursor before Writing
        $this->reset();
        //====================================================================//
        // Verify Lines List & Update if Needed
        foreach ($rowsData as $rowData) {
            //====================================================================//
            // Fetch Next Product Row
            $row = $this->getNextProductRow($rows);
            //====================================================================//
            // Ensure Row is from Correct Type, or Create a new one
            $row = $this->updateRowClass($row, $rowData);
            //====================================================================//
            // Update Row Contents
            $this
                ->updateScalarValues($row, $rowData)
                ->updatePrice($row, $rowData)
                ->updateRelated($row, $rowData)
            ;
            //====================================================================//
            // Push updated Row to Rows
            $rows[$this->rowsCursor] = $row;
        }
        //====================================================================//
        // Delete Remaining Lines
        while ($this->getNextProductRow($rows)) {
            unset($rows[$this->rowsCursor]);
        }

        return $rows;
    }

    /**
     * Get Next Valid Product Row
     *
     * @param AbstractRow[] $rows
     */
    public function getNextProductRow(array $rows): ?ProductRow
    {
        foreach ($rows as $index => $row) {
            //====================================================================//
            // Check Cursor
            if ($index <= $this->rowsCursor) {
                continue;
            }
            //====================================================================//
            // This is a Product Row
            if ($row instanceof ProductRow) {
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
    public function updateRowClass(?ProductRow $row, array $rowData): ProductRow
    {
        //====================================================================//
        // Detect if Simple or Catalog Row
        $isSimple = empty($rowData["related"]);
        //====================================================================//
        // Should be a Single Row
        if ($isSimple) {
            return ($row instanceof SingleRow) ? $row : new SingleRow();
        }
        //====================================================================//
        // Should be a Catalog Row => Related Changed ?
        $objectId = $rowData["related"] ?? null;
        if (($row instanceof CatalogRow) && ($objectId == $row->related?->toSplash())) {
            return $row;
        }
        //====================================================================//
        // New product Not Found
        if (!$productData = $this->getProductInfos((string) ObjectsHelper::id($objectId))) {
            return new SingleRow();
        }

        //====================================================================//
        // New product Found
        return match ($productData["type"]) {
            "shipping" => new ShippingRow(),
            "packaging" => new PackagingRow(),
            default => new CatalogRow(),
        };
    }

    /**
     * Update Row Contents from received Splash Data
     */
    public function updateScalarValues(ProductRow &$row, array $rowData): static
    {
        //====================================================================//
        // Update of Simple Contents
        if (array_key_exists("reference", $rowData)) {
            $row->reference = $rowData["reference"];
        }
        if (array_key_exists("description", $rowData)) {
            $row->description = $rowData["description"];
        }
        if (array_key_exists("quantity", $rowData)) {
            $row->quantity = $rowData["quantity"];
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
            $row->unitAmount = $unitAmount ? (string) $unitAmount : null;
        }
        //====================================================================//
        // Update of Tax ID
        if (array_key_exists("taxId", $rowData)) {
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
            $row->related->type = $productData["type"];
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
    }
}
