<?php

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
        return in_array($notification[WebhookArgs::OBJECT_TYPE], static::RELATED);
    }
}