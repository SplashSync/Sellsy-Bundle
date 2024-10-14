<?php

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