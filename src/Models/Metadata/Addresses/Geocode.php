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

namespace Splash\Connectors\Sellsy\Models\Metadata\Addresses;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class Geocode
{
    /**
     * Address Longitude
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("lat"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "[Geocode] Address Latitude"),
        //        SPL\Microdata("http://schema.org/", "")
    ]
    public ?string $lat = null;

    /**
     * Address Longitude
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("lng"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "[Geocode] Address Longitude"),
        //        SPL\Microdata("http://schema.org/", "")
    ]
    public ?string $lng = null;
}
