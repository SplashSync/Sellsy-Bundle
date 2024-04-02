<?php

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
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->geocode->lat;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->geocode->lng;
    }

    /**
     * @param float|null $latitude
     */
    public function setLatitude(?float $latitude): void
    {
        $this->geocode->latitude = $this->latitude = $latitude;
    }

    /**
     * @param float|null $longitude
     */
    public function setLongitude(?float $longitude): void
    {
        $this->geocode->longitude = $this->longitude = $longitude;
    }
}