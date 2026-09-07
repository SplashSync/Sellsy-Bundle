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

/**
 * Company Main Information Fields
 */
trait MainTrait
{
    /**
     * Company's reference.
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("reference"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(ThirdPartyFields::IDENTIFIER),
    ]
    public ?string $reference = null;

    #[
        Assert\Type("string"),
        Serializer\SerializedName("email"),
        Serializer\Groups(SplGroups::DEFAULT_LISTED),
        SPL\Template(ThirdPartyFields::EMAIL),
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
        SPL\Template(ThirdPartyFields::PHONE),
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
        SPL\Template(ThirdPartyFields::FAX),
    ]
    public ?string $faxNumber = null;

    /**
     * Company's capital.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("capital"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::VARCHAR, desc: "Company's capital"),
    ]
    public ?string $capital = null;

    /**
     * Note about the company.
     *
     * @var null|string
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("note"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::VARCHAR, desc: "Note about the company"),
    ]
    public ?string $note = "";

    public function setNote(?string $note): static
    {
        $this->note = (string) $note;

        return $this;
    }
}
