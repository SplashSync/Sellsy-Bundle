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

namespace Splash\Connectors\Sellsy\Dictionary;

use Splash\Connectors\Sellsy\Dictionary\Events\AddressConfig;
use Splash\Connectors\Sellsy\Dictionary\Events\ProductConfig;
use Splash\Connectors\Sellsy\Dictionary\Events\ThirdPartyConfig;
use Splash\Connectors\Sellsy\Models\Webhooks\AbstractWebhookConfig;

class WebhookConfig
{
    /**
     * Get All Available Webhooks Configs
     *
     * @return AbstractWebhookConfig[]
     */
    public static function all(): array
    {
        return array(
            new ThirdPartyConfig(),
            new AddressConfig(),
            new ProductConfig(),
        );
    }
}
