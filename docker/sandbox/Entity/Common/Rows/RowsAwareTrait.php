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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait RowsAwareTrait
{
    /**
     * @var Collection<ProductRow> Array of row items of type AbstractRow or its subclasses
     */
    //    #[Assert\NotNull]
    //    #[Assert\All(array(
    //        new Assert\Type(type: ProductRow::class)
    //    ))]
    //    #[Serializer\T("read")]
    //    #[ORM\OneToMany(mappedBy: "invoice", targetEntity: ProductRow::class)]
    public Collection $rows;

    //    /**
    //     * Returns only rows of type ProductRow.
    //     *
    //     * @return ProductRow[]
    //     */
    //    public function getProductRows(): array
    //    {
    //        return array_filter($this->rows, fn (AbstractRow $row) => $row instanceof ProductRow);
    //    }

    /**
     * Sets rows, ensuring they are of type AbstractRow or a subclass.
     *
     * @param ProductRow[] $rows
     */
    public function setRows(array $rows): void
    {
        //                $this->rows->toArray();

        $this->rows ??= new ArrayCollection($rows);

        //            $this->rows = array_filter($rows, fn ($row) => $row instanceof AbstractRow);
    }
}
