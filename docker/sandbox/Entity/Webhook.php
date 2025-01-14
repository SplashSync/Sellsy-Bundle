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
use App\Entity\Webhook\MetadataTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *  Class representing the Sellsy Webhook model.
 */
#[
    ORM\Entity(),
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
class Webhook extends AbstractSellsyObject
{
    use MetadataTrait;

    #[
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: Types::INTEGER),
        Serializer\Groups("read"),
    ]
    public ?int $id;

    #[
        Assert\Type("boolean"),
        ORM\Column(type: Types::BOOLEAN),
        Serializer\Groups("read"),
    ]
    public bool $isEnabled = true;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(type: Types::STRING),
        Serializer\Groups("read"),
    ]
    public string $type = "http";

    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(type: Types::STRING),
        Serializer\Groups("read"),
    ]
    public string $endpoint = "https://exemple.sellsy.com";

    #[
        Assert\Type("string"),
        ORM\Column(type: Types::STRING, nullable: true),
        Serializer\Groups("read"),
    ]
    public ?string $defaultChannel = null;

    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON),
        Serializer\Groups("read"),
    ]
    public array $configuration = array();
}
