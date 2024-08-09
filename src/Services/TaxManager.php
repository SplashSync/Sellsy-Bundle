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

use Exception;
use Splash\Client\Splash;
use Splash\Connectors\Sellsy\Connector\SellsyConnector;

/**
 * Manage Sellsy Account Taxes
 */
class TaxManager
{
    /**
     * @var array
     */
    private array $taxes = array();

    /**
     * Configure with Current API Connexion Settings
     */
    public function configure(SellsyConnector $connector): static
    {
        $taxes = $connector->getParameter("Taxes", array());
        $this->taxes = is_array($taxes) ? $taxes : array();

        return $this;
    }

    /**
     * Get Sellsy Taxes from API
     */
    public function fetchTaxesLists(SellsyConnector $connector): bool
    {
        //====================================================================//
        // Get Lists of Available Taxes from Api
        try {
            $response = $connector->getConnexion()->get("/taxes", array('limit' => "100"));
        } catch (Exception $e) {
            return Splash::log()->report($e);
        }
        if (!is_array($response) || !is_array($response['data'])) {
            return false;
        }
        //====================================================================//
        // Reformat results
        $taxes = array_combine(
            array_map(static function (array $taxItem) {
                return $taxItem["id"];
            }, $response['data']),
            $response['data']
        );
        //====================================================================//
        // Store in Connector Settings
        $connector->setParameter("Taxes", $taxes);

        return true;
    }

    /**
     * Get Tax Rate from ID
     */
    public function getRate(?int $taxId): float
    {
        return $this->taxes[$taxId]["rate"] ?? 0.0;
    }

    public function getTaxes(): array
    {
        return $this->taxes;
    }

    /**
     * Get Tax Label from ID
     */
    public function getLabel(?int $taxId): ?string
    {
        if (!$tax = $this->taxes[$taxId] ?? null) {
            return null;
        }

        return empty($tax["label"])
            ? sprintf("%.02f%% VAT", $this->getRate($taxId))
            : $tax["label"]
        ;
    }

    /**
     * Get Closest Tax ID for Given Tax Rate
     */
    public function findClosestTaxRate(float $taxRate): ?int
    {
        if (empty($taxRate) || $taxRate < 0.00) {
            return null;
        }

        $closestId = $closestRate = null;
        $taxList = $this->getTaxes();
        if (empty($taxList)) {
            return null;
        }

        //====================================================================//
        // Walk on Defined tax Rates
        foreach ($taxList as $tax) {
            $rate = $tax["rate"];
            if (null === $closestId || abs($rate - $taxRate) < abs($closestRate - $taxRate)) {
                $closestId = $tax["id"];
                $closestRate = $rate;
            }
        }

        //====================================================================//
        // Check if Closest Rate is Close Enough
        $threshold = 0.5; // Par exemple, tolérer une différence de 0.5
        if (abs($closestRate - $taxRate) > $threshold) {
            return null;
        }

        return $closestId;
    }
}
