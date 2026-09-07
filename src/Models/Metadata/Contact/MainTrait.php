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

use Splash\Connectors\Sellsy\Dictionary\Civility;
use Splash\Core\Dictionary\SplFields;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Splash\Templates\AddressFields;
use Splash\Templates\ThirdPartyFields;
use Symfony\Component\Serializer\Attribute as Serializer;
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
        Assert\Type("string"),
        Serializer\SerializedName("civility"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE)),
        SPL\Template(ThirdPartyFields::CIVILITY),
        SPL\Accessor(getter: "getCivilityFormated", setter: "setCivilityFormated"),
        SPL\IsNotTested(),
    ]
    public ?string $civility = null;

    /**
     * Contact's Firstname.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("first_name"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE, SplGroups::LIST)),
        SPL\Template(AddressFields::FIRSTNAME),
        SPL\Flags(listed: true),
    ]
    public ?string $first_name = null;

    /**
     * Contact's Lastname.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("last_name"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE, SplGroups::LIST, SplGroups::REQUIRED)),
        SPL\Template(AddressFields::LASTNAME),
        SPL\Flags(listed: true),
        SPL\IsRequired,
    ]
    public string $last_name;

    /**
     * Contact Job name.
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("position"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::VARCHAR, desc: "Contact job"),
    ]
    public ?string $position = null;

    #[
        Assert\Type("string"),
        Serializer\SerializedName("email"),
        Serializer\Groups(SplGroups::DEFAULT_LISTED),
        SPL\Template(AddressFields::EMAIL),
        SPL\Flags(listed: true),
    ]
    public ?string $email = null;

    #[
        Assert\Type("string"),
        Serializer\SerializedName("website"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE)),
        SPL\Template(ThirdPartyFields::URL),
    ]
    public ?string $website = null;

    #[
        Assert\Type("string"),
        Serializer\SerializedName("phone_number"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(AddressFields::PHONE),
    ]
    public ?string $phoneNumber = null;

    #[
        Assert\Type("string"),
        Serializer\SerializedName("mobile_number"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(ThirdPartyFields::MOBILE),
    ]
    public ?string $mobileNumber = null;

    #[
        Assert\Type("string"),
        Serializer\SerializedName("fax_number"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(AddressFields::FAX),
    ]
    public ?string $faxNumber = null;

    /**
     * Note about the Contact.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("note"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(AddressFields::DESCRIPTION),
    ]
    public ?string $note = null;

    public function getCivilityFormated(): ?string
    {
        return (string) Civility::toSplash($this->civility);
    }

    public function setCivilityFormated(?string $civility): self
    {
        //====================================================================//
        // Detect Changes
        if ($civility === Civility::toSplash($this->civility)) {
            return $this;
        }
        $this->civility = Civility::toApp($civility);

        return $this;
    }
}
