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

/**
 * Manage Access to Connector Scopes
 */
trait ConnectorScopesTrait
{
    /**
     * Get Sellsy Access Scope from API
     */
    public function fetchAccessScopes(): bool
    {
        return $this->scopesManager->fetchAccessScopes($this);
    }

    //    /**
    //     * Get Shopify Access Scope from Parameters
    //     *
    //     * @return string[]
    //     */
    //    public function getAccessScopes(): array
    //    {
    //        return $this->scopesManagers->getAccessScopes($this);
    //    }
    //
    //    /**
    //     * Get List of Missing Access Scopes.
    //     *
    //     * @return string[]
    //     */
    //    public function getMissingScopes() : array
    //    {
    //        return $this->scopesManagers->getMissingScopes($this);
    //    }
}
