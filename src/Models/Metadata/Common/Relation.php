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

namespace Splash\Connectors\Sellsy\Models\Metadata\Common;

use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Describe relation from an Object to Another
 */
class Relation
{
    #[
        Assert\NotNull,
        Assert\Type("int"),
        Serializer\SerializedName("id"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE, SplGroups::REQUIRED)),
    ]
    public int $id;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("type"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE, SplGroups::REQUIRED)),
    ]
    public string $type;
}
