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

namespace App\Entity\Webhook;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Webhook Metadata Fields for Sandbox
 */
trait MetadataTrait
{
    /**
     * Webhook creation date
     */
    #[
        Assert\Type("datetime"),
        ORM\Column(nullable: false),
        Serializer\Groups("read"),
    ]
    public DateTime $created;

    /**
     * Last Update Date
     */
    #[
        Assert\Type("datetime"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
    ]
    public ?DateTime $updated = null;

    //====================================================================//
    // Lifecycle Events
    //====================================================================//

    #[ORM\PrePersist()]
    public function onPrePersist(): void
    {
        $this->created = new DateTime();
    }

    #[ORM\PreUpdate()]
    public function onPreUpdate(): void
    {
        $this->updated = new DateTime();
    }
}
