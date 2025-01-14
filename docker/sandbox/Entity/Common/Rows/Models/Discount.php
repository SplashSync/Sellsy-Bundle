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

namespace App\Entity\Common\Rows\Models;

use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sellsy Sandbox Row Discount Storage
 */
class Discount
{
    /**
     * Discount percentage
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\Groups("read")
    ]
    public string $value = "0.00";

    /**
     * Discount type
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\Groups("read")
    ]
    public string $type = "percent";
}
