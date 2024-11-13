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

use DateTime;
use Doctrine\DBAL\Types\Types;
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
        ORM\Column(type: Types::DATE_MUTABLE),
        Serializer\Groups("read"),
        //        Serializer\Context(array('datetime_format' => 'Y-m-d')),
    ]
    public DateTime $date;

    /**
     * Invoice's Due Date
     */
    #[
        Assert\Type("datetime"),
        ORM\Column(type: Types::DATE_MUTABLE, nullable: true),
        Serializer\Groups("read")
    ]
    public ?DateTime $due_date = null;

    /**
     * Invoice's Created Date
     */
    #[
        Assert\Type("datetime"),
        ORM\Column(type: Types::DATETIME_MUTABLE),
        Serializer\Groups("read")
    ]
    public DateTime $created;
}
