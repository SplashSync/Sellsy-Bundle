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

namespace App\Entity\Invoice;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait MetadataTrait
{
    /**
     * Invoice's Date
     */
    #[
        Assert\NotNull,
        Assert\Type("datetime"),
        ORM\Column(),
        Serializer\Groups("read")
    ]
    public string $date;

    /**
     * Invoice's Due Date
     */
    #[
        Assert\NotNull,
        Assert\Type("datetime"),
        ORM\Column(),
        Serializer\Groups("read")
    ]
    public string $due_date;

    /**
     * Invoice's Created Date
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(),
        Serializer\Groups("read")
    ]
    public string $created;
}
