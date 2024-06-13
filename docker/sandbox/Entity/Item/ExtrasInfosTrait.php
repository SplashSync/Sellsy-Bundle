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

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Items Extras Fields for Sandbox: some IDs...
 */
trait ExtrasInfosTrait
{
    /**
     * Product's tax ID.
     */
    #[
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER, nullable: true),
        Serializer\Groups("read")
    ]
    public ?int $tax_id = null;

    /**
     * Product's unit ID.
     */
    #[
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER, nullable: true),
        Serializer\Groups("read")
    ]
    public ?int $unit_id = null;

    /**
     * Product's category ID.
     */
    #[
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER),
        Serializer\Groups("read")
    ]
    public int $category_id = 0;

    /**
     * Product's accounting code ID.
     */
    #[
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER, nullable: true),
        Serializer\Groups("read")
    ]
    public ?int $accounting_code_id = null;

    /**
     * Product's accounting purchase code ID.
     */
    #[
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER, nullable: true),
        Serializer\Groups("read")
    ]
    public ?int $accounting_purchase_code_id = null;
}
