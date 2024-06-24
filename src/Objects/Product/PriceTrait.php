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

use Splash\Connectors\Sellsy\Models\Connector\ConnectorTaxesTrait;
use Splash\Metadata\Attributes as SPL;
use Splash\Models\Objects\PricesTrait;

trait PriceTrait
{
    use PricesTrait;
    use ConnectorTaxesTrait;

    /**
     * Product's reference price excluding taxes.
     */
    #[
        SPL\Field(type: SPL_T_PRICE, desc: "Product's Price"),
        SPL\IsRequired,
        SPL\Microdata("http://schema.org/Product", "price")
    ]
    public ?array $splPrice = null;

    public function setSplPrice(array $splPrice): void
    {
        $this->splPrice = $splPrice;
    }

    /**
     * Build Fields using FieldFactory
     */
    protected function buildPriceFields(): void
    {
        //====================================================================//
        // Product Price
        self::fieldsFactory()->create(SPL_T_PRICE)
            ->identifier("price")
            ->name("Ref. Price")
            ->description("Product reference price")
            ->microData("http://schema.org/Product", "price")
        ;

        //====================================================================//
        // WholeSale Price
        self::fieldsFactory()->create(SPL_T_PRICE)
            ->identifier("price-wholesale")
            ->name("Wholesale price")
            ->description("Product wholesale price")
            ->microData("http://schema.org/Product", "wholesalePrice")
        ;
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
                if (!self::prices()->compare($current, $fieldData)) {
                    //====================================================================//
                    // Update reference price
                    $this->object->referencePrice = (string) (self::prices()->taxExcluded($fieldData) ?? 0.0);
                    $this->object->isReferencePriceTaxesFree = true;
                    //====================================================================//
                    // Update Tax Class
                    $taxPercent = self::prices()->taxPercent($fieldData);
                    if (null === $taxPercent) {
                        $this->object->taxId = 0;
                    } else {
                        $currentRate = $this->connector->getTaxManager()->getRate($this->object->taxId);
                        if (abs($taxPercent - $currentRate) > 0.01) {
                            $this->object->taxId = $this->connector->getTaxManager()->findClosestTaxRate($taxPercent)
                                ?? $this->object->taxId;
                        }
                    }
                    $this->needUpdate();
                }

                break;
            case "price-wholesale":
                $this->object->purchaseAmount = (string) (self::prices()->taxExcluded($fieldData) ?? 0.0);
                $this->needUpdate();

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
        return self::prices()->encode(
            (float) $this->object->referencePriceTaxesExc,
            $this->connector->getTaxManager()->getRate($this->object->taxId),
            null,
            $this->object->currency ?: "EUR"
        );
    }

    /**
     * @return null|array
     */
    private function getWholesalePrice(): ?array
    {
        return self::prices()->encode(
            (float) $this->object->purchaseAmount,
            0.00,
            null,
            $this->object->currency ?: "EUR"
        );
    }
}
