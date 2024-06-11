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

class ScopesManager
{
    /**
     * Get Sellsy Access Scope from APi
     */
    public function fetchAccessScopes(SellsyConnector $connector): bool
    {
        //====================================================================//
        // Get Lists of Available Scopes from Api
        try {
            $response = $connector->getConnexion()->get("/scopes");
        } catch (Exception $e) {
            return Splash::log()->report($e);
        }
        if (!is_array($response)) {
            return false;
        }
        //====================================================================//
        // Store in Connector Settings
        $connector->setParameter("Scopes", $response);

        return true;
    }
}
