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

namespace Splash\Connectors\Sellsy\Models\Metadata\Order;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class Owner
{
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("id"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_INT, desc: "[Owner] Owner's ID."),
        //        SPL\Microdata("http://schema.org/", "")
    ]
    public int $id;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("type"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "[Owner] Owner's type."),
        //        SPL\Microdata("http://schema.org/", "")
    ]
    public string $type;
}
