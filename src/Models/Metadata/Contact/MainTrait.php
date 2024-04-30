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

namespace Splash\Connectors\Sellsy\Models\Metadata\Contact;

use JMS\Serializer\Annotation as JMS;
use Splash\Client\Splash;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contact Main Information Fields
 */
trait MainTrait
{
    /**
     * Contact's Civility.
     */
    #[
        Assert\Choice(choices: array('mr', 'mrs', 'ms'), message: "Invalid civility value"),
        Assert\Type("string"),
        JMS\SerializedName("civility"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write")),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Contact's Civility"),
    ]
    public ?string $civility = null;

    /**
     * Contact's Firstname.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("first_name"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "List")),
        SPL\Field(desc: "Contact's Firstname"),
        SPL\Flags(listed: true),
    ]
    public ?string $first_name = null;

    /**
     * Contact's Lastname.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("last_name"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        SPL\Field(desc: "Contact's Lastname"),
        SPL\Flags(listed: true),
        SPL\IsRequired,
    ]
    public string $last_name;

    /**
     * Contact Job name.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("position"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Contact job"),
    ]
    public ?string $position = null;

    #[
        Assert\Type("string"),
        JMS\SerializedName("email"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_EMAIL, desc: "Contact email"),
        SPL\Microdata("http://schema.org/ContactPoint", "email")
    ]
    public ?string $email = null;

    #[
        Assert\Type("string"),
        JMS\SerializedName("website"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write")),
        SPL\Field(type: SPL_T_URL, desc: "Contact website"),
        SPL\Microdata("http://schema.org/Organization", "url")
    ]
    public ?string $website = null;

    #[
        Assert\Type("string"),
        JMS\SerializedName("phone_number"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_PHONE, desc: "Contact phone number"),
        SPL\Microdata("http://schema.org/Person", "telephone")
    ]
    public ?string $phoneNumber = null;

    #[
        Assert\Type("string"),
        JMS\SerializedName("mobile_number"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_PHONE, desc: "Contact mobile number"),
        SPL\Microdata("http://schema.org/Person", "telephone")
    ]
    public ?string $mobileNumber = null;

    #[
        Assert\Type("string"),
        JMS\SerializedName("fax_number"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_PHONE, desc: "Contact fax number"),
        SPL\Microdata("http://schema.org/faxNumber", "telephone")
    ]
    public ?string $faxNumber = null;

    /**
     * Note about the Contact.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("note"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Note about the contact"),
    ]
    public ?string $note = null;

    public function getCivility(): ?string
    {
        return $this->civility;
    }

    public function setCivility(?string $civility): self
    {
        switch ($civility) {
            case "mr":
            case "mrs":
            case "ms":
                $this->civility = $civility;

                break;
            case "Male":
                $this->civility = "mr";

                break;
            case "Female":
                $this->civility = "ms";

                break;
            default:
                Splash::log()->err("Invalid civility value: ".$civility);
        }

        return $this;
    }
}
