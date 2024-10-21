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
