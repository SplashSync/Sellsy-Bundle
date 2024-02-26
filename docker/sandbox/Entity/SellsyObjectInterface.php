<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * Sandbox Api Interface for All Sellsy Objects
 */
interface SellsyObjectInterface
{
    /**
     * Get Index Value for Serialized Items Data
     *
     * @return null|string
     *
     * @Ignore
     */
    public static function getItemIndex(): ?string;

    /**
     * Get Index Value for Serialized Collections Data
     *
     * @return null|string
     *
     * @Ignore
     */
    public static function getCollectionIndex(): ?string;

}