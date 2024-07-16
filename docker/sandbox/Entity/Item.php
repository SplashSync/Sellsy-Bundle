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
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Product model.
 */
#[
    ORM\Entity,
    ORM\HasLifecycleCallbacks,
    API\ApiResource(
        operations: array(
            new API\GetCollection(),
            new API\Post(),
            new API\Get(),
            new API\Put(),
            new API\Delete(),
        ),
        normalizationContext: array('groups' => array('read'))
    )
]
class Item extends AbstractSellsyObject
{
    use Item\MainTrait;
    use Item\ExtrasInfosTrait;
    use Item\MetadataTrait;
    use Item\PriceTrait;

    /**
     * Unique Identifier.
     */
    #[
        Assert\Type("integer"),
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: Types::INTEGER),
        Serializer\Groups("read")
    ]
    public int $id;

    /**
     * Product Type
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Assert\Choice(array("product", "service", "shipping", "packaging")),
        ORM\Column(type: Types::STRING, length: 16),
        Serializer\Groups("read")
    ]
    public string $type;
}
