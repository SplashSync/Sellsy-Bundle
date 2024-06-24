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

namespace App\Entity\Item;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Items Main Fields for Sandbox
 */
trait MainTrait
{
    /**
     * Product's name
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
    ]
    public ?string $name = null;

    /**
     * Product's reference
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: false),
        Serializer\Groups("read"),
    ]
    public string $reference = "";

    /**
     * Product's standard quantity
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: false),
        Serializer\Groups("read"),
    ]
    public string $standard_quantity = "1.00";

    /**
     * Product's description
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
    ]
    public ?string $description = null;

    /**
     * Is Name Included in Description
     */
    #[
        Assert\Type("boolean"),
        ORM\Column(nullable: false),
        Serializer\Groups("read"),
    ]
    public bool $is_name_included_in_description = false;
}
