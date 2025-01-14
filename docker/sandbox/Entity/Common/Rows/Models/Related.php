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

class Related
{
    /**
     * Row Relation ID.
     */
    #[
        Assert\Type("integer"),
        Serializer\Groups("read")
    ]
    public int $id;

    /**
     * Row Relation Type.
     */
    #[
        Assert\Type("string"),
        Serializer\Groups("read")
    ]
    public string $type;

    /**
     * Row Declination ID.
     */
    #[
        Assert\Type("integer"),
        Serializer\Groups("read")
    ]
    public ?int $declination_id = null;
}
