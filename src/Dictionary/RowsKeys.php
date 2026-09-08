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
 * Sales Documents Rows Api Keys Dictionary
 */
class RowsKeys
{
    /**
     * Rows collection of a sales document
     */
    const ROWS = "rows";

    /**
     * Row unique identifier, only known for already stored rows
     */
    const ID = "id";

    /**
     * Row type discriminator, only expected on row creation
     */
    const TYPE = "type";
}
