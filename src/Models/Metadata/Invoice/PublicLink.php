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

namespace Splash\Connectors\Sellsy\Models\Metadata\Invoice;

use Splash\Core\Dictionary\SplFields;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class PublicLink
{
    /**
     * Is Public Link Enabled ?
     *
     * @var bool
     */
    #[
        Assert\NotNull,
        Assert\Type("bool"),
        Serializer\SerializedName("enabled"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::BOOL, desc: "Is Public Link Enabled ?"),
    ]
    public bool $enabled = false;

    /**
     * Public Link URL
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("url"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::URL, desc: "Public Link URL"),
    ]
    public string $url = "";
}
