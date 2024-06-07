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

namespace App\Entity\Contact;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contact Information Fields for Sandbox
 */
trait ContactInfosTrait
{
    /**
     * Contact email
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
    ]
    public ?string $email = null;

    /**
     * Contact website
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
    ]
    public ?string $website = null;

    /**
     * Contact phone number
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
        Serializer\SerializedName("phone_number"),
    ]
    public ?string $phoneNumber = null;

    /**
     * Contact mobile number
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
        Serializer\SerializedName("mobile_number"),
    ]
    public ?string $mobileNumber = null;

    /**
     * Contact fax number
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
        Serializer\SerializedName("fax_number"),
    ]
    public ?string $faxNumber = null;

    /**
     * Contact note
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
    ]
    public ?string $note = null;
}
