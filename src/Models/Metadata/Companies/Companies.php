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

namespace Splash\Connectors\Sellsy\Models\Metadata\Companies;

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\Sellsy\Models\Metadata\Addresses\Addresses;
use Splash\Metadata\Attributes as SPL;
use Splash\Models\Helpers\ObjectsHelper;
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
        SPL\Choices(array(
            "prospect" => "Prospect",
            "client" => "Client",
            "supplier" => "Supplier",
        )),
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
        SPL\Microdata("http://schema.org/Person", "telephone")
    ]
    public ?string $mobileNumber = null;

    #[
        Assert\Type("string"),
        JMS\SerializedName("fax_number"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_PHONE, desc: "Company fax number"),
        SPL\Microdata("http://schema.org/faxNumber", "telephone")
    ]
    public ?string $faxNumber = null;

    /**
     * Company's capital.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("capital"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Company's capital"),
    ]
    public ?string $capital = null;

    /**
     * Company's reference.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("reference"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Company's reference"),
    ]
    public ?string $reference = null;

    /**
     * Note about the company.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("note"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Note about the company"),
    ]
    public ?string $note = null;

    //    /**
    //     * Company's accounting code id
    //     *
    //     * @var null|int
    //     */
    //    #[
    //        Assert\Type("integer"),
    //        JMS\SerializedName("accounting_code_id"),
    //        JMS\Groups(array("Read")),
    //        JMS\Type("integer"),
    //        SPL\Field(type: SPL_T_INT, desc: "Company's accounting code id"),
    //        SPL\IsReadOnly()
    //    ]
    //    public ?int $accountingCodeId = null;
    //
    //    /**
    //     * Company's accounting purchase code id
    //     *
    //     * @var null|int
    //     */
    //    #[
    //        Assert\Type("integer"),
    //        JMS\SerializedName("accounting_purchase_code_id"),
    //        JMS\Groups(array("Read")),
    //        JMS\Type("integer"),
    //        SPL\Field(type: SPL_T_INT, desc: "Company's accounting purchase code id"),
    //        SPL\IsReadOnly()
    //    ]
    //    public ?int $accountingPurchaseCodeId = null;
    //
    //    /**
    //     * Company's auxiliary code.
    //     *
    //     * @var null|string
    //     */
    //    #[
    //        Assert\Type("string"),
    //        JMS\SerializedName("auxiliary_code"),
    //        JMS\Type("string"),
    //        SPL\Field(type: SPL_T_VARCHAR, desc: "Company's auxiliary code"),
    //    ]
    //    public ?string $auxiliaryCode = null;
    //
    //    /**
    //     * Company's main contact id
    //     *
    //     * @var null|int
    //     */
    //    #[
    //        Assert\Type("integer"),
    //        JMS\SerializedName("main_contact_id"),
    //        JMS\Groups(array("Read")),
    //        JMS\Type("integer"),
    //        SPL\IsReadOnly(),
    //        SPL\Field(type: SPL_T_INT, desc: "Company's main contact id")
    //    ]
    //    public ?int $mainContactId = null;
    //
    //    /**
    //     * Company's invoicing contact id
    //     *
    //     * @var null|int
    //     */
    //    #[
    //        Assert\Type("integer"),
    //        JMS\SerializedName("invoicing_contact_id"),
    //        JMS\Groups(array("Read")),
    //        JMS\Type("integer"),
    //        SPL\IsReadOnly(),
    //        SPL\Field(type: SPL_T_INT, desc: "Company's main contact id")
    //    ]
    //    public ?int $invoicingContactId = null;
    //
    //    /**
    //     * Company's dunning contact id
    //     *
    //     * @var null|int
    //     */
    //    #[
    //        Assert\Type("integer"),
    //        JMS\SerializedName("dunning_contact_id"),
    //        JMS\Groups(array("Read")),
    //        JMS\Type("integer"),
    //        SPL\IsReadOnly(),
    //        SPL\Field(type: SPL_T_INT, desc: "Company's dunning contact id")
    //    ]
    //    public ?int $dunningContactId = null;
    //
    //    /**
    //     * Company's invoicing address id
    //     *
    //     * @var null|int
    //     */
    //    #[
    //        Assert\Type("integer"),
    //        JMS\SerializedName("invoicing_address_id"),
    //        JMS\Groups(array("Read")),
    //        SPL\Field(type: SPL_T_INT, desc: "Company's invoicing address id"),
    //        SPL\IsReadOnly()
    //    ]
    //    public ?int $invoicingAddressId = null;
    //
    //    /**
    //     * Company's delivery address id
    //     *
    //     * @var null|int
    //     */
    //    #[
    //        Assert\Type("integer"),
    //        JMS\SerializedName("delivery_address_id"),
    //        JMS\Groups(array("Read")),
    //        SPL\Field(type: SPL_T_INT, desc: "Company's delivery address id"),
    //        SPL\IsReadOnly()
    //    ]
    //    public ?int $deliveryAddressId = null;
    //
    //    /**
    //     * Company's rate category id
    //     *
    //     * @var null|int
    //     */
    //    #[
    //        Assert\Type("integer"),
    //        JMS\SerializedName("rate_category_id"),
    //        JMS\Groups(array("Read")),
    //        SPL\Field(type: SPL_T_INT, desc: "Company's rate category id"),
    //        SPL\IsReadOnly()
    //    ]
    //    public ?int $rateCategoryId = null;

    #[
        Assert\Type("boolean"),
        JMS\SerializedName("is_archived"),
        JMS\Type("boolean"),
        SPL\Field(type: SPL_T_BOOL, desc: "Is Company Archived", group: "Meta"),
    ]
    public bool $isArchived;

    /**
     * Social media information for the company.
     *
     * @var null|SocialUrls
     */
    #[
        Assert\Type(SocialUrls::class),
        JMS\SerializedName("social"),
        JMS\Type(SocialUrls::class),
        SPL\SubResource(),
        SPL\Accessor(factory: "createItem"),
    ]
    public ?SocialUrls $social = null;

    /**
     * Legal information for France about the company.
     *
     * @var null|LegalFrance
     */
    #[
        Assert\Type(LegalFrance::class),
        JMS\SerializedName("legal_france"),
        JMS\Type(LegalFrance::class),
        SPL\SubResource(),
        SPL\Accessor(factory: "createItem"),
    ]
    public ?LegalFrance $legalFrance = null;

    /**
     * Social media information for the company.
     *
     * @var null|RGPDConsent
     */
    #[
        Assert\Type(RGPDConsent::class),
        JMS\SerializedName("rgpd_consent"),
        JMS\Type(RGPDConsent::class),
        JMS\Groups(array("Read")),
        SPL\SubResource(),
        SPL\Accessor(factory: "createItem"),
    ]
    public ?RGPDConsent $rgpdConsent = null;

    #[
        Assert\Type("datetime"),
        JMS\SerializedName("created"),
        JMS\Type("DateTime"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_DATETIME, desc: "Company creation date", group: "Meta"),
        SPL\IsReadOnly,

    ]
    public DateTime $created;

    #[
        Assert\Type("datetime"),
        JMS\SerializedName("updated_at"),
        JMS\Type("DateTime"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_DATETIME, desc: "Last Update Date", group: "Meta"),
        SPL\IsReadOnly,
    ]
    public DateTime $updatedAt;

    //    public function getInvoicingAddressId(): ?string
    //    {
    //        if ($this->invoicingAddressId) {
    //            // "1234::Address"
    //            return ObjectsHelper::encode("Address", (string) $this->invoicingAddressId);
    //        }
    //
    //        return null;
    //    }

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

    //    public ?\Splash\Connectors\Sellsy\Models\Metadata\Addresses\Addresses $invoicing_address = null;

    #[
        JMS\SerializedName("_embed"),
        JMS\Type(CompanyEmbed::class),
        JMS\Groups(array("Read")),
    ]
    public CompanyEmbed $embed;

    #[
        JMS\Exclude,
        SPL\SubResource(Addresses::class, write: false),
    ]
    public ?Addresses $invoicingAddress = null;

    #[
        JMS\Exclude,
        SPL\SubResource(Addresses::class, write: false),
    ]
    public ?Addresses $deliveryAddress = null;

    public function __construct()
    {
        $this->created = new DateTime();
        $this->updatedAt = new DateTime();
    }

    #[JMS\PreSerialize]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getFaxNumberInt(): string
    {
        return "+33".$this->faxNumber;
    }

    #[JMS\PostDeserialize()]
    public function onPostSerialize(): void
    {
        //====================================================================//
        // Transfer Addresses from Embedded to Object
        $this->invoicingAddress = $this->embed->invoicingAddress ?? null;
        $this->deliveryAddress = $this->embed->deliveryAddress ?? null;
    }

    #[
        JMS\PostDeserialize(),
    ]
    public function getMyDebug(): void
    {
        //                dump($this);
        //                    dd($this);
    }
}
