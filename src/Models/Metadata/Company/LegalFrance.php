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

namespace Splash\Connectors\Sellsy\Models\Metadata\Company;

use Splash\Core\Dictionary\SplFields;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Splash\Templates\ThirdPartyFields;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class LegalFrance
{
    /**
     * Company's SIRET.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("siret"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(ThirdPartyFields::TAX_ID)
    ]
    public ?string $siret = null;

    /**
     * Company's SIREN.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("siren"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(ThirdPartyFields::DUNS)
    ]
    public ?string $siren = null;

    /**
     * Company's VAT.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("vat"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(ThirdPartyFields::VAT_ID)
    ]
    public ?string $vat = null;

    /**
     * Company's APE NAF code.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("ape_naf_code"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(ThirdPartyFields::NAICS)
    ]
    public ?string $apeNafCode = null;

    /**
     * Company's type.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("company_type"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::VARCHAR, desc: "Company Type"),
    ]
    public ?string $companyType = null;

    /**
     * Company's RCS immatriculation.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("rcs_immatriculation"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(ThirdPartyFields::ISIC_V4)
    ]
    public ?string $rcsImmatriculation = null;
}
