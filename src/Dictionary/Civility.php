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
 * Civility Types Dictionary
 */
class Civility
{
    const MAN = "mr";

    const WOMAN = "mrs";

    const LADY = "ms";

    const CHOICES = array(
        self::MAN => "Man",
        self::WOMAN => "Woman",
        self::LADY => "Lady",
    );

    /**
     * Convert Sellsy Civility to Splash Gender Type
     */
    public static function toSplash(?string $civility): ?string
    {
        return match($civility) {
            self::MAN => "0",
            self::WOMAN, self::LADY => "1",
            default => null
        };
    }

    /**
     * Convert Splash Gender Type to Sellsy Civility
     */
    public static function toSellsy(?string $genderType): ?string
    {
        return match($genderType) {
            "0" => self::MAN,
            "1" => self::WOMAN,
            default => null
        };
    }
}
