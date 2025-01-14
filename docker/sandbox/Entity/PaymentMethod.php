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
 * Class representing the Payment Methods model.
 */
#[
    ORM\Entity(),
    ApiResource(
        uriTemplate: "/payments/methods",
        operations: array(
            new API\GetCollection(),
        ),
    )
]
class PaymentMethod extends AbstractSellsyObject
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
     * Payment Method Label.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(type: Types::STRING, nullable: false),
    ]
    public string $label;
}
