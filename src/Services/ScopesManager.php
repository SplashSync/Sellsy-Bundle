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
use Splash\Connectors\Sellsy\Models\Connector\SellsyConnectorAwareTrait;

/**
 * Manage Splash Sellsy Connector Access Scopes
 */
class ScopesManager
{
    use SellsyConnectorAwareTrait;

    /**
     * Get Sellsy Access Scope from APi
     */
    public function fetchAccessScopes(): bool
    {
        //====================================================================//
        // Get Lists of Available Scopes from Api
        try {
            $response = $this->connector->getConnexion()->get("/scopes");
        } catch (Exception $e) {
            return Splash::log()->report($e);
        }
        if (!is_array($response)) {
            return false;
        }
        //====================================================================//
        // Store in Connector Settings
        $this->connector->setParameter("Scopes", $response);

        return true;
    }
}
