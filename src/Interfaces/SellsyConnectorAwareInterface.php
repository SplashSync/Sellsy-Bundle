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

namespace Splash\Connectors\Sellsy\Interfaces;

use Splash\Connectors\Sellsy\Connector\SellsyConnector;

/**
 * Any service that can be configured with the current Sellsy connector.
 *
 * Implemented by services using SellsyConnectorAwareTrait, so that the Locator
 * configures them on the fly when they are fetched.
 */
interface SellsyConnectorAwareInterface
{
    /**
     * Configure with Current Connector
     */
    public function configure(SellsyConnector $connector): static;
}
