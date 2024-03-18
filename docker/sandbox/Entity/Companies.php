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
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Companies model.
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
//        Assert\Choice(array("prospect", "client", "supplier")),
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
     * Company accounting code id
     */
    #[
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER, nullable: true),
    ]
    public ?int $accounting_code_id = null;

    /**
     * Company accounting purchase code id
     */
    #[
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER, nullable: true),
    ]
    public ?int $accounting_purchase_code_id = null;

    /**
     * Company auxiliary code
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $auxiliary_code = null;

    /**
     * Company main contact id
     */
    #[
        Assert\Type("integer"),
        ORM\Column(nullable: true),
    ]
    public ?int $main_contact_id = null;

    /**
     * Company invoicing contact id
     */
    #[
        Assert\Type("integer"),
        ORM\Column(nullable: true),
    ]
    public ?int $invoicing_contact_id = null;

    /**
     * Company dunning contact id
     */
    #[
        Assert\Type("integer"),
        ORM\Column(nullable: true),
    ]
    public ?int $dunning_contact_id = null;

    /**
     * Company invoicing address id
     */
    #[
        Assert\Type("integer"),
        ORM\Column(nullable: true),
    ]
    public ?int $invoicing_address_id = null;

    /**
     * Company delivery address id
     */
    #[
        Assert\Type("integer"),
        ORM\Column(nullable: true),
    ]
    public ?int $delivery_address_id = null;

    /**
     * Company rate category id
     */
    #[
        Assert\Type("integer"),
        ORM\Column(nullable: true),
    ]
    public ?int $rate_category_id = null;

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
     * Company owner
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true)
    ]
    public ?array $owner = array(
        "id" => null,
        "type" => null
    );

    /**
     * Company business segment
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true)
    ]
    public ?array $business_segment = array(
        "id" => null,
        "label" => null
    );

    /**
     * Company number of employees
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true)
    ]
    public ?array $number_of_employees = array(
        "id" => null,
        "label" => null
    );

    /**
     * Company marketing campaigns subscriptions
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true)
    ]
    public ?array $marketing_campaigns_subscriptions = null;


    #[ORM\PrePersist()]
    public function onPrePersist(): void
    {
        $this->created = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    #[ORM\PreUpdate()]
    public function onPreUpdate(): void
    {
        $this->updated_at = new \DateTime();
    }
}
