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

/**
 * Company Main Information Fields
 */
trait MainTrait
{
    /**
     * Company's name.
     */
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

    /**
     * Company's reference.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("reference"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Company's reference"),
    ]
    public ?string $reference = null;

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
}
