<?php

namespace Splash\Connectors\Sellsy\Dictionary\Events;

use Splash\Connectors\Sellsy\Models\Webhooks\AbstractWebhookConfig;

class ThirdPartyConfig extends AbstractWebhookConfig
{
    public const OBJECT_TYPE = "ThirdParty";

    public const EVENTS = array(
        "client.created", "client.updated", "client.deleted",
        "client.addressadded", "client.addressupdated", "client.addressremoved",
        "client.mainaddress", "client.transform",
        "client.archived", "client.unarchived",
        "client.editPrefs"
    );

    public const RELATED = array("client");
}