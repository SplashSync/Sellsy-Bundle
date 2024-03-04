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

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Api Metadata Model for Simple Object: Basic Fields.
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
#[SPL\SplashObject(
    name: "Company",
    description: "Sellsy Company API Object",
    ico: "fa fa-user"
)]
class Companies
{
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("id"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
    ]
    public string $id;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("type"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "List", "Required")),
        SPL\Field(desc: "Company type"),
        SPL\Flags(listed: true),
        SPL\IsRequired,
    ]
    public string $type;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("name"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        SPL\Field(desc: "Company name"),
        SPL\Flags(listed: true),
        SPL\IsRequired,
    ]
    public string $name;

    #[
        Assert\Type("string"),
        JMS\SerializedName("email"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_EMAIL, desc: "Company email"),
        SPL\Microdata("http://schema.org/ContactPoint", "email")
    ]
    public ?string $email = null;

    #[
        Assert\Type("string"),
        JMS\SerializedName("website"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write")),
        SPL\Field(type: SPL_T_URL, desc: "Company website"),
        SPL\Microdata("http://schema.org/Organization", "url")
    ]
    public ?string $website = null;

    #[
        Assert\Type("string"),
        JMS\SerializedName("phone_number"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_PHONE, desc: "Company phone number"),
        SPL\Microdata("http://schema.org/Person", "telephone")
    ]
    public ?string $phoneNumber = null;

    #[
        Assert\Type("string"),
        JMS\SerializedName("mobile_number"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_PHONE, desc: "Company mobile number"),
    ]
    public ?string $mobileNumber = null;

    #[
        Assert\Type("string"),
        JMS\SerializedName("fax_number"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_PHONE, desc: "Company fax number"),
    ]
    public ?string $faxNumber = null;

    #[
        Assert\Type("array<string>"),
        JMS\SerializedName("social"),
        JMS\Type("array<string>"),
        SPL\Field(type: SPL_T_INLINE, desc: "Company social networks"),
    ]
    public ?array $social = array(
        "twitter" => null,
        "facebook" => null,
        "linkedin" => null,
        "viadeo" => null,
    );

    public function getFaxNumberInt(): string
    {
        return "+33".$this->faxNumber;
    }

    //    #[
    //        Assert\Type("array"),
    //        JMS\SerializedName("legal_france"),
    //        JMS\Type("array"),
    //        SPL\Field(type: SPL_T_INLINE, desc: "Company legal information for France"),
    //    ]
    //    public ?array $legalFrance = [
    //        "siret" => null,
    //        "siren" => null,
    //        "vat" => null,
    //        "ape_naf_code" => null,
    //        "company_type" => null,
    //        "rcs_immatriculation" => null,
    //    ];
    //
    //    /**
    //     * Client's phone.
    //     */
    //    #[
    //        Assert\Type("string"),
    //        JMS\SerializedName("phone"),
    //        JMS\Type("string"),
    //        SPL\Field(type: SPL_T_PHONE, desc: "This is User Phone"),
    //    ]
    //    public ?string $phone = null;
    //
    //    /**
    //     * Just a Bool Flag.
    //     */
    //    #[
    //        Assert\Type("bool"),
    //        JMS\SerializedName("bool"),
    //        JMS\Type("bool"),
    //        SPL\Field(),
    //    ]
    //    public ?bool $bool = null;
    //
    //    /**
    //     * Just an integer.
    //     */
    //    #[
    //        Assert\Type("int"),
    //        JMS\SerializedName("int"),
    //        JMS\Type("int"),
    //        SPL\Field(),
    //    ]
    //    public ?int $int = null;
    //
    //    /**
    //     * Client's website Url.
    //     */
    //    #[
    //        Assert\Type("string"),
    //        JMS\SerializedName("website"),
    //        JMS\Type("string"),
    //        SPL\Field(type: SPL_T_URL)
    //    ]
    //    public ?string $website = null;
    //
    //    /**
    //     * ISO Language
    //     */
    //    #[
    //        Assert\Type("string"),
    //        JMS\SerializedName("language"),
    //        JMS\Type("string"),
    //        SPL\Field(type: SPL_T_LANG)
    //    ]
    //    public ?string $language = null;
    //
    //    /**
    //     * ISO Currency
    //     */
    //    #[
    //        Assert\Type("string"),
    //        JMS\SerializedName("currency"),
    //        JMS\Type("string"),
    //        SPL\Field(type: SPL_T_CURRENCY)
    //    ]
    //    public ?string $currency = null;
    //
    //    /**
    //     * Address country as ISO_3166-1 alpha-3.
    //     */
    //    #[
    //        Assert\Type("string"),
    //        JMS\SerializedName("countryId"),
    //        JMS\Type("string"),
    //        SPL\Field(type: SPL_T_COUNTRY)
    //    ]
    //    public ?string $countryId = null;
    //
    //    /**
    //     * Date Field
    //     */
    //    #[
    //        Assert\Type("datetime"),
    //        JMS\SerializedName("date"),
    //        JMS\Type("DateTime"),
    //        SPL\Field(type: SPL_T_DATE)
    //    ]
    //    public ?DateTime $date = null;
    //
    //    /**
    //     * Datetime Field
    //     */
    //    #[
    //        Assert\Type("datetime"),
    //        JMS\SerializedName("datetime"),
    //        JMS\Type("DateTime"),
    //        SPL\Field(type: SPL_T_DATETIME)
    //    ]
    //    public ?DateTime $datetime = null;
    //
    //    /**
    //     * Splash Price Field
    //     */
    //    #[
    //        Assert\Type("array"),
    //        JMS\SerializedName("price"),
    //        JMS\Type("array"),
    //        SPL\Field(type: SPL_T_PRICE)
    //    ]
    //    public ?array $price = null;
    //
    //    /**
    //     * Splash Image Field
    //     */
    //    #[
    //        Assert\Type("array"),
    //        JMS\SerializedName("image"),
    //        JMS\Type("array"),
    //        SPL\Field(type: SPL_T_IMG),
    //        SPL\IsReadOnly,
    //    ]
    //    public ?array $image = null;
    //
    //    /**
    //     * Splash File Field
    //     */
    //    #[
    //        Assert\Type("array"),
    //        JMS\SerializedName("file"),
    //        JMS\Type("array"),
    //        SPL\Field(type: SPL_T_FILE),
    //        SPL\IsReadOnly,
    //    ]
    //    public ?array $file = null;
}
