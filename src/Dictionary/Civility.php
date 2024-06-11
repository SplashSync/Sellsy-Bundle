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

use Splash\Models\Objects\ThirdParty\Civility as BaseCivility;

/**
 * Civility Types Dictionary
 */
class Civility extends BaseCivility
{
    public const MAN = "mr";

    public const WOMAN = "mrs";

    public const LADY = "ms";

    public const MAP = array(
        self::MAN => self::MALE,
        self::WOMAN => self::FEMALE,
        self::LADY => self::FEMALE,
    );
}
