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

namespace Splash\Connectors\Sellsy\Services\Api;

use Exception;
use Splash\Connectors\Sellsy\Dictionary\ApiScopes;
use Splash\Connectors\Sellsy\Interfaces\SellsyConnectorAwareInterface;
use Splash\Connectors\Sellsy\Models\Connector\SellsyConnectorAwareTrait;
use Splash\Core\Client\Splash;

/**
 * Manage Splash Sellsy Connector Access Scopes
 */
class ScopesManager implements SellsyConnectorAwareInterface
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

    /**
     * Get Scopes the Sellsy Account makes available
     *
     * @return string[]
     */
    public function getAvailableScopes(): array
    {
        $scopes = $this->connector->getParameter("Scopes", array());

        return is_array($scopes) ? array_values(array_filter($scopes, "is_string")) : array();
    }

    /**
     * Get Scopes the Connector needs
     *
     * @return string[]
     */
    public function getRequiredScopes(): array
    {
        return ApiScopes::REQUIRED;
    }

    /**
     * Get Required Scopes the Account does NOT make available
     *
     * @return string[]
     */
    public function getMissingScopes(): array
    {
        return array_values(array_diff($this->getRequiredScopes(), $this->getAvailableScopes()));
    }

    /**
     * Check if the Account grants every Scope the Connector needs
     */
    public function hasRequiredScopes(): bool
    {
        return empty($this->getMissingScopes());
    }
}
