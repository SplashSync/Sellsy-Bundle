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

namespace Splash\Connectors\Sellsy\Models\Actions;

use Splash\OpenApi\Models\Action\AbstractListAction;

class SellsyListAction extends AbstractListAction
{
    /**
     * {@inheritdoc}
     */
    protected function extractData(array $rawResponse): array
    {
        return parent::extractData($rawResponse['data'] ?? array());
    }

    /**
     * {@inheritdoc}
     */
    protected function extractTotal(array $rawResponse, array $params = null): int
    {
        return $rawResponse['pagination']['total'] ?? parent::extractTotal($rawResponse, $params);
    }
}
