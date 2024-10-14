<?php

namespace Splash\Connectors\Sellsy\Dictionary\Events;

use Splash\Connectors\Sellsy\Models\Webhooks\AbstractWebhookConfig;

class ProductConfig extends AbstractWebhookConfig
{
    public const OBJECT_TYPE = "Product";

    public const EVENTS = array(
        "item.created", "item.updated", "item.deleted",
        "item.declinationactivated", "item.updatedRC",
        "item.itemassociated", "item.itemunassociated",
        "item.updateCharacteristics", "item.updateSpecifications",
    );

    public const RELATED = array("item");
}