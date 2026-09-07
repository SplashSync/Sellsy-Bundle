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

namespace Splash\Connectors\Sellsy\Models\Metadata;

use Splash\Connectors\Sellsy\Models\Metadata\Address\GeocodeAwareTrait;
use Splash\Core\Dictionary\SplFields;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Splash\Templates\Address\PostalAddressFields;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Api Metadata Model for Company & Contacts Addresses.
 */
#[SPL\SplashObject(
    name: "Address",
    description: "Sellsy Address API Object",
    ico: "fa fa-user"
)]
class Address
{
    use GeocodeAwareTrait;

    /**
     * ID of the Address.
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("id"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::LIST)),
    ]
    //====================================================================//
    // Nullable on purpose: an object being created has no id yet, and the
    // Visitor reads this property to identify it.
    public ?string $id = null;

    /**
     * Name of the Address.
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("name"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE)),
        SPL\Template(PostalAddressFields::COMPANY),
        SPL\Field(group: "Address"),
    ]
    public string $name;

    /**
     * First line of the address.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("address_line_1"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(PostalAddressFields::STREET),
    ]
    public ?string $addressFirstLine = null;

    /**
     * Second line of the address.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("address_line_2"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(PostalAddressFields::POST_OFFICE_BOX_NUMBER),
    ]
    public ?string $addressSecondLine = null;

    /**
     * Third line of the address.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("address_line_3"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(PostalAddressFields::EXTENDED),
    ]
    public ?string $addressThirdLine = null;

    /**
     * Fourth line of the address.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("address_line_4"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(
            type: SplFields::VARCHAR,
            name: "[ADD4] Address extension",
            desc: "Fourth line of the address",
            group: "Address"
        ),
    ]
    public ?string $addressFourthLine = null;

    /**
     * Address's postal code.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("postal_code"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(PostalAddressFields::POSTAL_CODE),
    ]
    public ?string $postalCode = null;

    /**
     * Address's city.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("city"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(PostalAddressFields::CITY),
    ]
    public ?string $city = null;

    /**
     * Address's country.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("country"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(PostalAddressFields::COUNTRY_NAME),
    ]
    public ?string $country = null;

    /**
     * Address's country ISO code.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("country_code"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(PostalAddressFields::COUNTRY),
    ]
    public ?string $countryCode = null;

    /**
     * Is address invoicing address.
     *
     * @var bool
     */
    #[
        Assert\Type("bool"),
        Serializer\SerializedName("is_invoicing_address"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(
            type: SplFields::BOOL,
            name: "Is Invoicing",
            desc: "Is address invoicing address ?",
            group: "Address"
        ),
        SPL\IsNotTested(),
    ]
    public bool $isInvoicingAddress = false;

    /**
     * Is address delivery address.
     *
     * @var bool
     */
    #[
        Assert\Type("bool"),
        Serializer\SerializedName("is_delivery_address"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(
            type: SplFields::BOOL,
            name: "Is Delivery",
            desc: "Is address delivery address ?",
            group: "Address"
        ),
        SPL\IsNotTested(),
    ]
    public bool $isDeliveryAddress = false;
}
