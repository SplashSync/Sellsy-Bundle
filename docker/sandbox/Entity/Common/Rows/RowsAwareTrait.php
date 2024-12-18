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
     * @var Collection<SingleRow> Array of row items of type AbstractRow or its subclasses
     */
    #[Serializer\Groups("read")]
    #[ORM\OneToMany(
        mappedBy: "invoice",
        targetEntity: SingleRow::class,
        cascade: array("all"),
        orphanRemoval: true,
    )]
    public Collection $rows;

    /**
     * Sets rows with Existing IDs Detection.
     *
     * @param ProductRow[] $rows
     */
    public function setRows(array $rows): void
    {
        //====================================================================//
        // Deleted All Existing Rows
        foreach ($this->rows as $row) {
            $row->invoice = null;
            $this->rows->removeElement($row);
        }
        //====================================================================//
        // Update from Received Rows
        foreach ($rows as $row) {
            $row->invoice = $this;
            $this->rows->add($row);
        }
    }
}
