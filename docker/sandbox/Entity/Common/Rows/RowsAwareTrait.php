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
use App\Entity\Common\Rows\Models\ProductRow;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait RowsAwareTrait
{
    /**
     * @var AbstractRow[] Array of row items of type AbstractRow or its subclasses
     */
    #[Assert\NotNull]
    #[Assert\All(array(
        new Assert\Type(type: AbstractRow::class)
    ))]
    #[ORM\Column(type: Types::JSON)]
    #[Serializer\Groups("read")]
    public array $rows = array();

    /**
     * Returns only rows of type ProductRow.
     *
     * @return ProductRow[]
     */
    public function getProductRows(): array
    {
        return array_filter($this->rows, fn (AbstractRow $row) => $row instanceof ProductRow);
    }

    /**
     * Sets rows, ensuring they are of type AbstractRow or a subclass.
     *
     * @param AbstractRow[] $rows
     */
    public function setRows(array $rows): void
    {
        $this->rows = array_filter($rows, fn ($row) => $row instanceof AbstractRow);
    }
}
