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

use Splash\Core\Dictionary\SplFields;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
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
        Serializer\SerializedName("geocode"),
        // Geocode is computed by Sellsy: read it, never send it back.
        // This is what JMS SkipWhenEmpty was guarding against.
        Serializer\Groups(array(SplGroups::READ)),
    ]
    public ?Geocode $geocode = null;

    /**
     * Address Longitude
     */
    #[
        Serializer\Ignore,
        SPL\Field(
            type: SplFields::DOUBLE,
            desc: "[Geocode] Address Latitude",
            group: "Address"
        ),
        SPL\IsReadOnly,
    ]
    protected ?float $latitude = null;

    /**
     * Address Longitude
     */
    #[
        Serializer\Ignore,
        SPL\Field(
            type: SplFields::DOUBLE,
            desc: "[Geocode] Address Longitude",
            group: "Address"
        ),
        SPL\IsReadOnly,
    ]
    protected ?float $longitude = null;

    /**
     * @return null|float
     */
    public function getLatitude(): ?float
    {
        return $this->geocode?->lat;
    }

    /**
     * @return null|float
     */
    public function getLongitude(): ?float
    {
        return $this->geocode?->lng;
    }

    /**
     * @param null|float $latitude
     */
    public function setLatitude(?float $latitude): void
    {
        // Ensure geocode is Defined
        $this->geocode ??= new Geocode();
        // Update Latitude
        $this->geocode->lat = $this->latitude = $latitude;
    }

    /**
     * @param null|float $longitude
     */
    public function setLongitude(?float $longitude): void
    {
        // Ensure geocode is Defined
        $this->geocode ??= new Geocode();
        // Update Longitude
        $this->geocode->lng = $this->longitude = $longitude;
    }
}
