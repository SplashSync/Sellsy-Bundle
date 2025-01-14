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
