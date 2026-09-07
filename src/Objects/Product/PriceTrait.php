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

namespace Splash\Connectors\Sellsy\Objects\Product;

use Splash\Core\Helpers\PricesHelper;
use Splash\Templates\ProductFields;

trait PriceTrait
{
    /**
     * Build Fields using FieldFactory
     */
    protected function buildPriceFields(): void
    {
        //====================================================================//
        // Product Price
        self::fieldsFactory()
            ->createFromTemplate("price", ProductFields::PRICE)
            ->isListed(false)
        ;

        //====================================================================//
        // WholeSale Price
        self::fieldsFactory()->createFromTemplate("price-wholesale", ProductFields::WHOLESALE_PRICE);
    }

    /**
     * Read requested Field
     */
    protected function getPriceFields(string $key, string $fieldName): void
    {
        //====================================================================//
        // READ FIELD
        switch ($fieldName) {
            case "price":
                $this->out[$fieldName] = $this->getSplashPrice();

                break;
            case "price-wholesale":
                $this->out[$fieldName] = $this->getWholesalePrice();

                break;
            default:
                return;
        }

        unset($this->in[$key]);
    }

    /**
     * Write Given Fields
     */
    protected function setPriceFields(string $fieldName, ?array $fieldData): void
    {
        if (null === $fieldData) {
            return;
        }

        //====================================================================//
        // READ FIELD
        switch ($fieldName) {
            case "price":
                $current = $this->getSplashPrice();
                $taxManager = $this->connector->getLocator()->getTaxManager();

                if (!PricesHelper::compare($current, $fieldData)) {
                    //====================================================================//
                    // Update reference price
                    $this->object->referencePrice = (string) (PricesHelper::taxExcluded($fieldData) ?? 0.0);
                    $this->object->isReferencePriceTaxesFree = true;

                    //====================================================================//
                    // Update Tax Class
                    $taxPercent = PricesHelper::taxPercent($fieldData);
                    if (null === $taxPercent) {
                        $this->object->taxId = 0;
                    } else {
                        $currentRate = $taxManager->getRate($this->object->taxId);
                        if (abs($taxPercent - $currentRate) > 0.01) {
                            $this->object->taxId = $taxManager->findClosestTaxRate($taxPercent)
                                ?? $this->object->taxId;
                        }
                    }

                    $this->needUpdate();
                }

                break;
            case "price-wholesale":
                $purchaseAmount = PricesHelper::taxExcluded($fieldData) ?? 0.0;
                if (abs($purchaseAmount - (float) $this->object->purchaseAmount) > 0.001) {
                    $this->object->purchaseAmount = (string) $purchaseAmount;
                    $this->needUpdate();
                }

                break;
            default:
                return;
        }
        unset($this->in[$fieldName]);
    }

    /**
     * @return null|array
     */
    private function getSplashPrice(): ?array
    {
        return PricesHelper::encode(
            (float) $this->object->referencePriceTaxesExc,
            $this->connector->getLocator()->getTaxManager()->getRate($this->object->taxId),
            null,
            $this->object->currency ?: $this->connector->getDefaultCurrency()
        );
    }

    /**
     * @return null|array
     */
    private function getWholesalePrice(): ?array
    {
        return PricesHelper::encode(
            (float) $this->object->purchaseAmount,
            $this->connector->getLocator()->getTaxManager()->getRate($this->object->taxId),
            null,
            $this->object->currency ?: $this->connector->getDefaultCurrency()
        );
    }
}
