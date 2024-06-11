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

namespace App\Entity\Company;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Companies Extras Fields for Sandbox: Legal IDs, Social Urls, GDPR Consents...
 */
trait ExtrasInfosTrait
{
    /**
     * Company social networks links
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true),
        Serializer\Groups("read"),
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
        ORM\Column(type: Types::JSON, nullable: true),
        Serializer\Groups("read"),
        Serializer\SerializedName("legal_france"),
    ]
    public ?array $legalFrance = array(
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
        ORM\Column(type: Types::JSON, nullable: true),
        Serializer\Groups("read"),
        Serializer\SerializedName("rgpd_consent"),
    ]
    public ?array $rgpdConsent = array(
        "email" => false,
        "sms" => false,
        "phone" => false,
        "postalMail" => false,
        "custom" => false
    );
}
