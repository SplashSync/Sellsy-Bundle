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

namespace Splash\Connectors\Sellsy\Models\Webhooks;

use Splash\Bundle\Models\AbstractConnector;
use Splash\Connectors\Sellsy\Dictionary\WebhookArgs;

abstract class AbstractWebhookConfig
{
    const OBJECT_TYPE = "";

    const EVENTS = array();

    const RELATED = array();

    /**
     * Get Target Object Name
     */
    public function getObjectType() : string
    {
        return static::OBJECT_TYPE;
    }

    /**
     * Get Tracked Events Names
     *
     * @return string[]
     */
    public function getEvents() : array
    {
        return static::EVENTS;
    }

    /**
     * Get Webhook Event default Channel
     */
    public function getChanel(AbstractConnector $abstractConnector): string
    {
        return sprintf("%s::%s", $abstractConnector->getWebserviceId(), $this->getObjectType());
    }

    /**
     * Get Webhook Configuration
     */
    public function getConfiguration(AbstractConnector $abstractConnector, string $url): array
    {
        $whConfig = array(
            "endpoint" => $url,
            "default_channel" => $this->getChanel($abstractConnector),
            "configuration" => array()
        );
        foreach ($this->getEvents() as $event) {
            $whConfig["configuration"][] = array("id" => $event, 'isEnabled' => true);
        }

        return $whConfig;
    }

    /**
     * Check if this Config Manage Received Related Type
     */
    public function handle(array $notification): bool
    {
        return in_array($notification[WebhookArgs::OBJECT_TYPE], static::RELATED, true);
    }
}
