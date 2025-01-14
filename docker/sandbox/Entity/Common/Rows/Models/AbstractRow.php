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

use App\Entity\Invoice;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\MappedSuperclass]
abstract class AbstractRow
{
    #[
        Assert\Type("integer"),
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: Types::INTEGER),
        Serializer\Groups(array("read", "write"))
    ]
    public int $id;

    #[
        ORM\ManyToOne(targetEntity: Invoice::class, inversedBy: "rows"),
    ]
    public ?Invoice $invoice;

    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(type: Types::STRING),
        Serializer\Groups(array("read", "write"))
    ]
    public string $type;

    /**
     * Get Row Entity ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
