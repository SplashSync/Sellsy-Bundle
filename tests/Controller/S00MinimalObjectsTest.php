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

namespace Splash\Connectors\Sellsy\Test\Controller;

use Exception;
use Splash\Connectors\Sellsy\Connector\SellsyConnector;
use Splash\Tests\Tools\ObjectsCase;

class S00MinimalObjectsTest extends ObjectsCase
{
    /**
     * Connector Server ID
     */
    const CONNECTOR = 'ThisIsSandBoxWsId';

    /**
     * Test Connector Loading
     *
     * @throws Exception
     */
    public function testConnectorLoading(): void
    {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector(self::CONNECTOR);
        $this->assertInstanceOf(SellsyConnector::class, $connector);
    }

    /**
     * Test Connector Connect
     *
     * @throws Exception
     */
    public function testConnectorConnect(): void
    {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector(self::CONNECTOR);
        $this->assertInstanceOf(SellsyConnector::class, $connector);
        $this->assertTrue($connector->connect());
    }
}
