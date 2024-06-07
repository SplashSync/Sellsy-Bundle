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
 * Contact Main Fields for Sandbox
 */
trait MainTrait
{
    /**
     * Contact Firstname
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
    ]
    public ?string $firstName = null;

    /**
     * Contact Lastname
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column,
        Serializer\Groups("read"),
    ]
    public string $lastName;

    /**
     * Contact Job
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
    ]
    public ?string $position = null;

    /**
     * Contact's Gender
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
    ]
    public ?string $civility = null;
}
