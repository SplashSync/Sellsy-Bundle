<?php

namespace Splash\Connectors\Sellsy\Dictionary\Events;

use Splash\Connectors\Sellsy\Models\Webhooks\AbstractWebhookConfig;

class AddressConfig extends AbstractWebhookConfig
{
    public const OBJECT_TYPE = "Address";

    public const EVENTS = array(
        "people.created", "people.updated", "people.deleted",
        "people.created_address", "people.updated_address", "people.deleted_address",
        "people.updatedmain_address",
        "people.archived", "people.unarchived",
        "third_party_contact.created", "third_party_contact.main", "third_party_contact.deleted",
    );

    public const RELATED = array("people");
}