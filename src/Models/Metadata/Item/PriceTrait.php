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

namespace Splash\Connectors\Sellsy\Models\Metadata\Item;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Splash\Models\Objects\PricesTrait;

trait PriceTrait
{
    use PricesTrait;

    /**
     * Item's reference price excluding taxes.
     */
    #[
        JMS\Exclude(),
        SPL\Field(type: SPL_T_PRICE, desc: "Item's Price"),
        SPL\IsRequired,
        SPL\Microdata("http://schema.org/Product", "price")
    ]
    public ?array $splPrice = null;

    public function setSplPrice(array $splPrice): void
    {
        $this->splPrice = $splPrice;
    }

    /**
     * @return null|array
     */
    public function getSplPrice(): ?array
    {
        $taxId = $this->splPrice['tax_id'] ?? null;
        $taxIncluded = $this->splPrice['is_reference_price_taxes_free'] ?? false;
        $priceHT = isset($this->splPrice['reference_price'])
            ? (float) $this->splPrice['reference_price']
            : (float) ($this->splPrice['reference_price_taxes_exc'] ?? 0.0);
        $priceTTC = !$taxIncluded && isset($this->splPrice['reference_price'])
            ? (float) $this->splPrice['reference_price']
            : null;

        // Récupérer le taux de taxe
        $tax = $this->getTaxRate($taxId);
        $currency = $this->splPrice['currency'] ?? $this->getDefaultCurrency();

        // Construire le tableau de prix
        $this->splPrice ??= self::prices()->encode(
            $priceHT,
            $tax,
            $priceTTC,
            $currency
        );

        return $this->splPrice;
    }

    /**
     * @param null|int $taxId
     *
     * @return float
     */
    private function getTaxRate(?int $taxId): float
    {
        // Vérification de sécurité
        if (null === $taxId) {
            return 19.6;
        }

        // Charger le taux de taxe (ici c'est en dur, à remplacer par une vraie logique)
        return 19.6;
    }

    /**
     * @return string
     */
    private function getDefaultCurrency(): string
    {
        return "EUR";
    }
}
