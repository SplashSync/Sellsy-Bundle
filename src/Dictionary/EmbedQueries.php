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

/**
 * Embedded Resources Queries Dictionary
 *
 * Sellsy only returns embedded resources when they are explicitly asked
 * for on the item's uri.
 */
class EmbedQueries
{
    /**
     * Addresses embedded on Companies & Contacts item reads
     */
    const ADDRESSES = "?embed[]=invoicing_address&embed[]=delivery_address";
}
