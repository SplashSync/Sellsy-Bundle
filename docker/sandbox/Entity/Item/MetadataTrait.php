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

namespace App\Entity\Item;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Items Metadata Fields for Sandbox
 */
trait MetadataTrait
{
    /**
     * Is Product Archived
     */
    #[
        Assert\Type("boolean"),
        ORM\Column(nullable: false),
        Serializer\Groups("read"),
    ]
    public bool $is_archived = false;

    /**
     * Is Product declined
     */
    #[
        Assert\Type("boolean"),
        ORM\Column(nullable: false),
        Serializer\Groups("read"),
    ]
    public bool $is_declined = false;
}
