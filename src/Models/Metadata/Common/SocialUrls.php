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

use Splash\Core\Dictionary\SplFields;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class SocialUrls
{
    #[
        Assert\Type("string"),
        Serializer\SerializedName("twitter"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::URL, desc: "[Social] Company Twitter Page"),
        SPL\Microdata("http://schema.org/URL", "twitter")
    ]
    public ?string $twitter = null;

    // TODO: Impossible d'attribuer ou modifier la valeur  des champs facebook,
    // linkedin et viadeo depuis Toolkit web pour les contacts

    #[
        Assert\Type("string"),
        Serializer\SerializedName("facebook"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::URL, desc: "[Social] Company Facebook Page"),
        SPL\Microdata("http://schema.org/URL", "facebook")
    ]
    public ?string $facebook = null;

    #[
        Assert\Type("string"),
        Serializer\SerializedName("linkedin"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::URL, desc: "[Social] Company LinkedIn Page"),
        SPL\Microdata("http://schema.org/URL", "linkedin")
    ]
    public ?string $linkedin = null;

    #[
        Assert\Type("string"),
        Serializer\SerializedName("viadeo"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::URL, desc: "[Social] Company Viadeo Page"),
        SPL\Microdata("http://schema.org/URL", "viadeo")
    ]
    public ?string $viadeo = null;
}
