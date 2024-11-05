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

namespace Splash\Connectors\Sellsy\Models\Connector;

use Splash\Client\Splash;
use Splash\Connectors\Sellsy\Connector\SellsyConnector;
use Splash\Connectors\Sellsy\Connexion\SellsyApiV1Connexion;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;

/**
 * Use API V2 Connexion to build an API V1 Connexion
 */
trait SellsyApiV1AwareTrait
{
    /**
     * Configure with Current API Connexion Settings
     */
    protected function executeApiV1Request(SellsyConnector $connector, array $request): ?array
    {
        //====================================================================//
        // Encode Raw request Parameters
        $query = array(
            'request' => 1,
            'io_mode' => 'json',
            'do_in' => (string) json_encode($request),
        );
        //====================================================================//
        // Execute Request
        $response = $this->getApiV1Connexion($connector)->post("/", $query);
        //====================================================================//
        // Extract Response
        if (!is_array($response)) {
            return null;
        }
        //====================================================================//
        // Detect API Error
        if (!empty($response["error"])) {
            return Splash::log()->errNull($response["error"]);
        }
        if (($response["error"] ?? false) == "success") {
            return $response;
        }

        return null;
    }
    /**
     * Configure with Current API Connexion Settings
     */
    private function getApiV1Connexion(SellsyConnector $connector): ConnexionInterface
    {
        $connexion = $connector->getConnexion();
        //====================================================================//
        // Only When using Live Connexion
        if ($connector->isSandbox()) {
            return $connexion;
        }

        //====================================================================//
        // Only When using Live Connexion
        return new SellsyApiV1Connexion(
            $connexion->getTemplate()->headers,
        );
    }
}
