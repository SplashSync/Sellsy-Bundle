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

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
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
        JMS\SerializedName("siret"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Company SIRET Number"),
        SPL\Microdata("http://schema.org/Organization", "taxID")
    ]
    public ?string $siret = null;

    /**
     * Company's SIREN.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("siren"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Company SIREN Number"),
        SPL\Microdata("http://schema.org/Organization", "duns")
    ]
    public ?string $siren = null;

    /**
     * Company's VAT.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("vat"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Company VAT Code"),
        SPL\Microdata("http://schema.org/Organization", "vatID")
    ]
    public ?string $vat = null;

    /**
     * Company's APE NAF code.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("ape_naf_code"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Company APE NAF Code"),
        SPL\Microdata("http://schema.org/Organization", "naics")
    ]
    public ?string $apeNafCode = null;

    /**
     * Company's type.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("company_type"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Company Type"),
    ]
    public ?string $companyType = null;

    /**
     * Company's RCS immatriculation.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("rcs_immatriculation"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Company RCS immatriculation"),
        SPL\Microdata("http://schema.org/Organization", "isicV4")
    ]
    public ?string $rcsImmatriculation = null;
}
