<?php

namespace Splash\Connectors\Sellsy\Dictionary;

class WebhookArgs
{
    /**
     * Index for Event Data Storage in Webhooks
     *
     * @var string
     */
    const INDEX = "notif";

    /**
     * Key for Event type
     */
    const ACTION = "event";

    /**
     * Key for Related Object Type
     */
    const OBJECT_TYPE = "relatedtype";

    /**
     * Key for Related Object ID
     */
    const OBJECT_ID = "relatedid";

    /**
     * Event type for Data Created
     */
    const CREATED = "created";

    /**
     * Event type for Data Updated
     */
    const UPDATED = "updated";

    /**
     * Event type for Data Deleted
     */
    const DELETED = "deleted";

    /**
     * Event type for Data Any Other Events in Tests
     */
    const ANYTHING = "anything";

    /**
     * Convert Sellsy Event Names to Splash Commit Type
     */
    public static function toSplashAction(string $action) : string
    {
        return match($action) {
            "created" => SPL_A_CREATE,
            "deleted" => SPL_A_DELETE,
            default => SPL_A_UPDATE,
        };
    }
}