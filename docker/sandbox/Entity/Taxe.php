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

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata as API;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Tax model.
 */
#[
    ORM\Entity(),
    ApiResource(
        operations: array(
            new API\GetCollection(),
            new API\Post(),
            new API\Get(),
            new API\Put(),
            new API\Delete(),
        ),
    )
]
class Taxe extends AbstractSellsyObject
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
     * Tax Rate.
     */
    #[
        Assert\NotNull,
        Assert\Type("float"),
        ORM\Column(type: Types::FLOAT, nullable: false),
    ]
    public float $rate;

    /**
     * Tax Rate Label.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(type: Types::STRING, nullable: false),
    ]
    public string $label;

    /**
     * Is Active Flag.
     */
    #[
        Assert\NotNull,
        Assert\Type("bool"),
        ORM\Column(type: Types::BOOLEAN, nullable: false),
    ]
    public bool $isActive = true;

    /**
     * Is EcoTax Flag.
     */
    #[
        Assert\NotNull,
        Assert\Type("bool"),
        ORM\Column(type: Types::BOOLEAN, nullable: false),
    ]
    public bool $isEcotax = false;
}
