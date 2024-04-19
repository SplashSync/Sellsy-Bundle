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
use App\Entity\Company\AddressesTrait;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Company model.
 */
#[
    ORM\Entity,
    API\ApiResource(
        operations: array(
            new API\GetCollection(),
            new API\Post(),
            new API\Get(),
            new API\Put(),
            new API\Delete(),
        )
    )
]
class Companies extends AbstractSellsyObject
{
    use AddressesTrait;

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
     * Company Type
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Assert\Choice(array("prospect", "client", "supplier")),
        ORM\Column,
    ]
    public string $type;

    /**
     * Company name
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column,
    ]
    public string $name;

    /**
     * Company email
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $email = null;

    /**
     * Company website
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $website = null;

    /**
     * Company phone number
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $phone_number = null;

    /**
     * Company mobile number
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $mobile_number = null;

    /**
     * Company fax number
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $fax_number = null;

    /**
     * Company capital
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $capital = null;

    /**
     * Company reference
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $reference = null;

    /**
     * Company note
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $note = null;

    /**
     * Company creation date
     */
    #[
        Assert\Type("datetime"),
        ORM\Column(nullable: false),
    ]
    public DateTime $created;

    /**
     * Last Update Date
     */
    #[
        Assert\Type("datetime"),
        ORM\Column(nullable: false),
    ]
    public DateTime $updated_at;

    /**
     * Is Company Archived
     */
    #[
        Assert\Type("boolean"),
        ORM\Column(nullable: false),
    ]
    public bool $is_archived = false;

    /**
     * Company social networks links
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true)
    ]
    public ?array $social = array(
        "facebook" => null,
        "twitter" => null,
        "linkedin" => null,
        "viadeo" => null
    );

    /**
     * Company legal information for France
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true)
    ]
    public ?array $legal_france = array(
        "siret" => null,
        "siren" => null,
        "vat" => null,
        "ape_naf_code" => null,
        "company_type" => null,
        "rcs_immatriculation" => null
    );

    /**
     * Company legal information for France
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true)
    ]
    public ?array $rgpd_consent = array(
        "email" => false,
        "sms" => false,
        "phone" => false,
        "postal_mail" => false,
        "custom" => false
    );

    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true)
    ]
    public ?array $embed = array(
        "invoicing_address" => null,
        "delivery_address" => null
    );

    public function __construct()
    {
        $this->created = $this->updated_at = new DateTime();
    }

    #[ORM\PreUpdate()]
    public function onPreUpdate(): void
    {
        $this->updated_at = new DateTime();
    }

    public function getInvoicingAddress(): ?Addresses
    {
        return $this->embed["invoicing_address"];
    }

    public function setInvoicingAddress(?Addresses $invoicingAddress): void
    {
        $this->invoicing_address = $this->embed["invoicing_address"] = $invoicingAddress;
    }

    public function getDeliveryAddress(): ?Addresses
    {
        return $this->embed["delivery_address"];
    }

    public function setDeliveryAddress(?Addresses $deliveryAddress): void
    {
        $this->delivery_address = $this->embed["delivery_address"] = $deliveryAddress;
    }
}
