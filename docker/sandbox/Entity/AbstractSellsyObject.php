<?php

namespace App\Entity;

abstract class AbstractSellsyObject implements SellsyObjectInterface
{

    /**
     * @inheritDoc
     */
    public static function getItemIndex(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public static function getCollectionIndex(): ?string
    {
        return 'data';
    }
}