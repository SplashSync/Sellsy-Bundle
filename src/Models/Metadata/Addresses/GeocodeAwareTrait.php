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

/**
 * Access to Geocode Structural Data without Subresource
 */
trait GeocodeAwareTrait
{
    /**
     * Latitude and longitude of the Address.
     *
     * @var null|Geocode
     */
    #[
        Assert\Type(Geocode::class),
        JMS\SerializedName("geocode"),
        JMS\Type(Geocode::class),
    ]
    public ?Geocode $geocode = null;

    /**
     * Address Longitude
     */
    #[
        JMS\Exclude(),
        SPL\Field(type: SPL_T_DOUBLE, desc: "[Geocode] Address Latitude"),
    ]
    private ?float $latitude = null;

    /**
     * Address Longitude
     */
    #[
        JMS\Exclude(),
        SPL\Field(type: SPL_T_DOUBLE, desc: "[Geocode] Address Longitude"),
    ]
    private ?float $longitude = null;

    /**
     * @return null|float
     */
    public function getLatitude(): ?float
    {
        return $this->geocode->lat;
    }

    /**
     * @return null|float
     */
    public function getLongitude(): ?float
    {
        return $this->geocode->lng;
    }

    /**
     * @param null|float $latitude
     */
    public function setLatitude(?float $latitude): void
    {
        $this->geocode->latitude = $this->latitude = $latitude;
    }

    /**
     * @param null|float $longitude
     */
    public function setLongitude(?float $longitude): void
    {
        $this->geocode->longitude = $this->longitude = $longitude;
    }
}
