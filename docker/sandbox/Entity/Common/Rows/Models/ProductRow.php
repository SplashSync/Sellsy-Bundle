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

use App\Entity\Common\Rows\CatalogRow;
use App\Entity\Common\Rows\Related;
use App\Entity\Common\Rows\SingleRow;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\MappedSuperclass()]
#[Serializer\DiscriminatorMap(
    typeProperty: "type",
    mapping: array(
        SingleRow::DATATYPE => SingleRow::class,
        CatalogRow::DATATYPE => CatalogRow::class,
    )
)]
abstract class ProductRow extends AbstractRow
{
    /**
     * Row's reference
     */
    #[
        Assert\Type("string"),
        ORM\Column(type: Types::STRING),
        Serializer\Groups("read")
    ]
    public ?string $reference = null;

    /**
     * Row's description
     */
    #[
        Assert\Type("string"),
        ORM\Column(type: Types::STRING),
        Serializer\Groups("read")
    ]
    public ?string $description = null;

    /**
     * Row's quantity
     */
    #[
        Assert\Type("string"),
        ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2),
        Serializer\Groups("read")
    ]
    public ?string $unit_amount = null;

    /**
     * Row's quantity
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(type: Types::INTEGER),
        Serializer\Groups("read")
    ]
    public string $quantity;

    /**
     * Row's quantity
     */
    #[
        Assert\NotNull,
        Assert\Type("integer"),
        ORM\Column(),
        Serializer\Groups("read")
    ]
    public int $tax_id = 0;

    /**
     * Row's quantity
     */
    #[
        Serializer\Ignore()
    ]
    public ?Related $related = null;
}
