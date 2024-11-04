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

namespace App\Entity\Common\Rows;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Relation
{
    #[
        Assert\NotNull,
        Assert\Type("int"),
        ORM\Column(type: Types::INTEGER, nullable: false),
        ORM\Id,
        Serializer\Groups(array("read", "write", "required"))
    ]
    public int $id;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(nullable: false),
        Serializer\Groups(array("read", "write", "required"))
    ]
    public string $type;
}
