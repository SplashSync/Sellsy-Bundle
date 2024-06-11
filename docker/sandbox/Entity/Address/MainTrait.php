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

namespace App\Entity\Address;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Companies & Contacts Address Data
 */
trait MainTrait
{
    /**
     * Address's name
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column,
        Serializer\Groups("read")
    ]
    public string $name;

    /**
     * First line of the address
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
        Serializer\SerializedName("address_line_1"),
    ]
    public ?string $addressLine1 = null;

    /**
     * First line of the address
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
        Serializer\SerializedName("address_line_2"),
    ]
    public ?string $addressLine2 = null;

    /**
     * First line of the address
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
        Serializer\SerializedName("address_line_3"),
    ]
    public ?string $addressLine3 = null;

    /**
     * First line of the address
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
        Serializer\SerializedName("address_line_4"),
    ]
    public ?string $addressLine4 = null;

    /**
     * Address's postal code
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
        Serializer\SerializedName("postal_code"),
    ]
    public ?string $postalCode = null;

    /**
     * Address's city
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read")
    ]
    public ?string $city = null;

    /**
     * Address's country
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read")
    ]
    public ?string $country = null;

    /**
     * Address's country ISO code
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
        Serializer\SerializedName("country_code"),
    ]
    public ?string $countryCode = null;

    /**
     * Is address invoicing address
     */
    #[
        Assert\Type("bool"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
        Serializer\SerializedName("is_invoicing_address"),
    ]
    public ?bool $isInvoicingAddress = null;

    /**
     * Is address delivery address
     */
    #[
        Assert\Type("bool"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
        Serializer\SerializedName("is_delivery_address"),
    ]
    public ?bool $isDeliveryAddress = null;

    /**
     * Latitude and longitude of the Address
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true),
        Serializer\Groups("read")
    ]
    public ?array $geocode = array(
        "lat" => null,
        "lng" => null,
    );
}
