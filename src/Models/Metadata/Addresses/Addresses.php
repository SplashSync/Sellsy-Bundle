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
 * Api Metadata Model for Address Object: Basic Fields.
 */
#[SPL\SplashObject(
    name: "Address",
    description: "Sellsy Address API Object",
    ico: "fa fa-user"
)]
class Addresses
{
    use GeocodeAwareTrait;

    /**
     * Id of the Address.
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("id"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
    ]
    public string $id;

    /**
     * Name of the Address.
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("name"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        SPL\Field(desc: "Address name"),
        SPL\Flags(listed: true),
        SPL\IsRequired,
    ]
    public string $name;

    /**
     * First line of the address.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("address_line_1"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_TEXT, desc: "First line of the address"),
    ]
    public ?string $addressFirstLine = null;

    /**
     * Second line of the address.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("address_line_2"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_TEXT, desc: "Second line of the address"),
    ]
    public ?string $addressSecondLine = null;

    /**
     * Third line of the address.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("address_line_3"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_TEXT, desc: "Third line of the address"),
    ]
    public ?string $addressThirdLine = null;

    /**
     * Fourth line of the address.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("address_line_4"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_TEXT, desc: "Fourth line of the address"),
    ]
    public ?string $addressFourthLine = null;

    /**
     * Address's postal code.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("postal_code"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Address's postal code"),
    ]
    public ?string $postalCode = null;

    /**
     * Address's city.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("city"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Address's city"),
    ]
    public ?string $city = null;

    /**
     * Address's country.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("country"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Address's country"),
    ]
    public ?string $country = null;

    /**
     * Address's country ISO code.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("country_code"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Address's country ISO code"),
    ]
    public ?string $countryCode = null;

    /**
     * Is address invoicing address.
     *
     * @var bool
     */
    #[
        Assert\Type("bool"),
        JMS\SerializedName("is_invoicing_address"),
        JMS\Type("bool"),
        SPL\Field(type: SPL_T_BOOL, desc: "Is address invoicing address ?"),
    ]
    public bool $isInvoicingAddress = false;

    /**
     * Is address delivery address.
     *
     * @var bool
     */
    #[
        Assert\Type("bool"),
        JMS\SerializedName("is_delivery_address"),
        JMS\Type("bool"),
        SPL\Field(type: SPL_T_BOOL, desc: "Is address delivery address ?"),
    ]
    public bool $isDeliveryAddress = false;
}
