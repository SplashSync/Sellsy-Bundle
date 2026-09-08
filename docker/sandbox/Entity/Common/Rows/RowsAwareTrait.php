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

use App\Entity\Common\Rows\Models\ProductRow;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

trait RowsAwareTrait
{
    /**
     * @var Collection<SellsyRow> Array of row items of type AbstractRow or its subclasses
     */
    #[Serializer\Groups("read")]
    #[ORM\OneToMany(
        mappedBy: "invoice",
        targetEntity: SellsyRow::class,
        cascade: array("all"),
        orphanRemoval: true,
    )]
    public Collection $rows;

    /**
     * Sets rows with Existing IDs Detection.
     *
     * Sellsy identifies an updated row by its id, and only expects the values
     * that change: a received row that matches a stored one is merged into it,
     * any other row is created, and the rows left aside are deleted.
     *
     * @param ProductRow[] $rows
     */
    public function setRows(array $rows): void
    {
        //====================================================================//
        // Index Stored Rows by Id
        $stored = array();
        foreach ($this->rows as $row) {
            $stored[$row->getId()] = $row;
        }
        //====================================================================//
        // Walk on Received Rows
        $wanted = array();
        foreach ($rows as $row) {
            $current = $stored[$row->getId()] ?? null;
            $wanted[] = $current ? self::mergeRow($current, $row) : $row;
        }
        //====================================================================//
        // Delete Rows that were not received
        foreach ($this->rows as $row) {
            if (!in_array($row, $wanted, true)) {
                $row->invoice = null;
                $this->rows->removeElement($row);
            }
        }
        //====================================================================//
        // Store Received Rows
        foreach ($wanted as $row) {
            $row->invoice = $this;
            if (!$this->rows->contains($row)) {
                $this->rows->add($row);
            }
        }
    }

    /**
     * Copy the values received on a row to the stored one
     *
     * @template TRow of object
     *
     * @param TRow $current
     * @param TRow $received
     *
     * @return TRow
     */
    private static function mergeRow(object $current, object $received): object
    {
        foreach ((new \ReflectionObject($received))->getProperties() as $property) {
            //====================================================================//
            // Only take the values the request did carry
            if (!$property->isInitialized($received) || in_array($property->getName(), array("id", "invoice"), true)) {
                continue;
            }
            $property->setValue($current, $property->getValue($received));
        }

        return $current;
    }
}
