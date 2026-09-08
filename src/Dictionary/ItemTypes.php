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
 * Catalog Items Types Dictionary
 */
class ItemTypes
{
    /**
     * A Sold Good
     */
    const PRODUCT = "product";

    /**
     * A Sold Service
     */
    const SERVICE = "service";

    /**
     * Delivery Charges
     */
    const SHIPPING = "shipping";

    /**
     * Packaging Charges
     */
    const PACKAGING = "packaging";

    const CHOICES = array(
        self::PRODUCT => "Product",
        self::SERVICE => "Service",
        self::SHIPPING => "Shipping",
        self::PACKAGING => "Packaging",
    );
}
