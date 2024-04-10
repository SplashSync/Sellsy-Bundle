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

namespace App\Entity;

use ApiPlatform\Metadata as API;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Address model.
 */
#[
    ORM\Entity,
]
#[API\ApiResource(
    uriTemplate: '/companies/{companyId}/addresses',
    operations: array(
        new API\GetCollection(),
        new API\Post()
    ),
    uriVariables: array(
        'companyId' => new API\Link(toProperty: 'company', fromClass: Companies::class),
    )
)]
#[API\ApiResource(
    uriTemplate: '/companies/{companyId}/addresses/{id}',
    operations: array(
        new API\Get(),
        new API\Put(),
        new API\Delete(),
    ),
    uriVariables: array(
        'companyId' => new API\Link(toProperty: 'company', fromClass: Companies::class),
        'id' => new API\Link(fromClass: Addresses::class),
    )
)]
class Addresses extends AbstractSellsyObject
{
    /**
     * Unique Identifier.
     */
    #[
        Assert\Type("integer"),
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: Types::INTEGER),
    ]
    public int $id;

    /**
     * Address's name
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column,
    ]
    public string $name;

    /**
     * First line of the address
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $address_line_1 = null;

    /**
     * First line of the address
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $address_line_2 = null;

    /**
     * First line of the address
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $address_line_3 = null;

    /**
     * First line of the address
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $address_line_4 = null;

    /**
     * Address's postal code
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $postal_code = null;

    /**
     * Address's city
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $city = null;

    /**
     * Address's country
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $country = null;

    /**
     * Address's country ISO code
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $country_code = null;

    /**
     * Is address invoicing address
     */
    #[
        Assert\Type("bool"),
        ORM\Column(nullable: true),
    ]
    public ?string $is_invoicing_address = null;

    /**
     * Is address delivery address
     */
    #[
        Assert\Type("bool"),
        ORM\Column(nullable: true),
    ]
    public ?string $is_delivery_address = null;

    /**
     * Latitude and longitude of the Address
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true)
    ]
    public ?array $geocode = array(
        "lat" => null,
        "lng" => null,
    );
}
