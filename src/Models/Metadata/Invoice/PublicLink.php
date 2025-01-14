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

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
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
        JMS\SerializedName("enabled"),
        JMS\Type("bool"),
        SPL\Field(type: SPL_T_BOOL, desc: "Is Public Link Enabled ?"),
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
        JMS\SerializedName("url"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_URL, desc: "Public Link URL"),
    ]
    public string $url = "";
}
