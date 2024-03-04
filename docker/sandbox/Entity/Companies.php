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

namespace App\Entity;

use ApiPlatform\Metadata as API;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Companies model.
 */
#[
    ORM\Entity(),
    API\ApiResource(
        operations: array(
            new API\GetCollection(),
            new API\Post(),
            new API\Get(),
            new API\Put(),
            new API\Delete(),
        )
    )
]
class Companies extends AbstractSellsyObject
{
    /**
     * Unique Identifier.
     */
    #[
        Assert\Type("integer"),
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: Types::INTEGER),
    ]
    public int $id;

    /**
     * Company Type
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(),
    ]
    public string $type;

    /**
     * Company name
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(),
    ]
    public string $name;

    /**
     * Company email
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $email = null;

    /**
     * Company website
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $website = null;

    /**
     * Company phone number
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $phone_number = null;

    /**
     * Company mobile number
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $mobile_number = null;

    /**
     * Company fax number
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
    ]
    public ?string $fax_number = null;
}
