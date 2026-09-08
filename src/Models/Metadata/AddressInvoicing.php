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

use Splash\Core\Dictionary\SplFields;
use Splash\Metadata\Attributes as SPL;
use Splash\Templates\Accounting\AccountingBillingFields;

/**
 * Api Metadata Model for Invoicing Addresses.
 *
 * Same Sellsy address, seen as an invoicing address: only the field templates
 * change, so that Splash reads them as schema.org/billingAddress instead of
 * plain postal address fields.
 *
 * Properties are redeclared for their templates only: serialization, validation
 * and accessors are inherited from the parent, PHP attributes are not.
 */
class AddressInvoicing extends Address
{
    /**
     * Name of the Address.
     *
     * @var string
     */
    #[
        SPL\Template(AccountingBillingFields::BILLING_COMPANY),
    ]
    public string $name;

    /**
     * First line of the address.
     *
     * @var null|string
     */
    #[
        SPL\Template(AccountingBillingFields::BILLING_STREET),
    ]
    public ?string $addressFirstLine = null;

    /**
     * Second line of the address.
     *
     * @var null|string
     */
    #[
        SPL\Template(AccountingBillingFields::BILLING_POST_OFFICE_BOX),
    ]
    public ?string $addressSecondLine = null;

    /**
     * Third line of the address.
     *
     * @var null|string
     */
    #[
        SPL\Template(AccountingBillingFields::BILLING_STREET_EXT),
    ]
    public ?string $addressThirdLine = null;

    /**
     * Address's postal code.
     *
     * @var null|string
     */
    #[
        SPL\Template(AccountingBillingFields::BILLING_POSTAL_CODE),
    ]
    public ?string $postalCode = null;

    /**
     * Address's city.
     *
     * @var null|string
     */
    #[
        SPL\Template(AccountingBillingFields::BILLING_CITY),
    ]
    public ?string $city = null;

    /**
     * Address's country.
     *
     * @var null|string
     */
    #[
        SPL\Template(AccountingBillingFields::BILLING_COUNTRY_NAME),
    ]
    public ?string $country = null;

    /**
     * Address's country ISO code.
     *
     * @var null|string
     */
    #[
        SPL\Template(AccountingBillingFields::BILLING_COUNTRY),
    ]
    public ?string $countryCode = null;

    /**
     * Fourth line of the address.
     *
     * @var null|string
     */
    #[
        SPL\Field(
            type: SplFields::VARCHAR,
            name: "[ADD4] Address extension",
            desc: "Fourth line of the address",
            group: "Billing"
        ),
    ]
    public ?string $addressFourthLine = null;

    /**
     * Is address invoicing address.
     *
     * @var bool
     */
    #[
        SPL\Field(
            type: SplFields::BOOL,
            name: "Is Invoicing",
            desc: "Is address invoicing address ?",
            group: "Billing"
        ),
    ]
    public bool $isInvoicingAddress = false;

    /**
     * Is address delivery address.
     *
     * @var bool
     */
    #[
        SPL\Field(
            type: SplFields::BOOL,
            name: "Is Delivery",
            desc: "Is address delivery address ?",
            group: "Billing"
        ),
    ]
    public bool $isDeliveryAddress = false;

    /**
     * Address Latitude
     */
    #[
        SPL\Field(
            type: SplFields::DOUBLE,
            desc: "[Geocode] Address Latitude",
            group: "Billing"
        ),
        SPL\IsReadOnly,
    ]
    protected ?float $latitude = null;

    /**
     * Address Longitude
     */
    #[
        SPL\Field(
            type: SplFields::DOUBLE,
            desc: "[Geocode] Address Longitude",
            group: "Billing"
        ),
        SPL\IsReadOnly,
    ]
    protected ?float $longitude = null;
}
