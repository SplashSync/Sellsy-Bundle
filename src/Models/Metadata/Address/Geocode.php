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

namespace Splash\Connectors\Sellsy\Models\Metadata\Address;

use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Geocode
{
    /**
     * Address Latitude
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("lat"),
        Serializer\Groups(SplGroups::DEFAULT),
    ]
    public ?float $lat = null;

    /**
     * Address Longitude
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("lng"),
        Serializer\Groups(SplGroups::DEFAULT),
    ]
    public ?float $lng = null;
}
