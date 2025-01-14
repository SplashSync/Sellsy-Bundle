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

namespace App\Entity\Common\Rows;

use App\Entity\Common\Rows\Models\AbstractRow;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Generic Sellsy Row data Storage
 */
#[ORM\Entity()]
#[ORM\Table("invoice_rows")]
#[ORM\HasLifecycleCallbacks()]
class SellsyRow extends AbstractRow
{
    use Traits\RelatedTrait;
    use Traits\DiscountTrait;
    use Traits\TaxTrait;

    /**
     * Row's reference
     */
    #[
        Assert\Type("string"),
        ORM\Column(type: Types::STRING, nullable: true),
        Serializer\Groups("read")
    ]
    public ?string $reference = null;

    /**
     * Row's description
     */
    #[
        Assert\Type("string"),
        ORM\Column(type: Types::STRING, nullable: true),
        Serializer\Groups("read")
    ]
    public ?string $description = null;

    /**
     * Row's quantity
     */
    #[
        Assert\Type(array("null", "string")),
        ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2),
        Serializer\Groups("read")
    ]
    public float $unit_amount = 0.0;

    /**
     * Row's quantity
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(type: Types::STRING),
        Serializer\Groups("read")
    ]
    public string $quantity;

    /**
     * Set Unit Amount as Float
     */
    public function setUnitAmount(?string $value): void
    {
        $this->unit_amount = (float) $value;
    }
}
