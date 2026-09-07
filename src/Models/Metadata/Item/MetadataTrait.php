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

namespace Splash\Connectors\Sellsy\Models\Metadata\Item;

use Splash\Core\Dictionary\SplFields;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Splash\Templates\ProductFields;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product Metadata Fields
 */
trait MetadataTrait
{
    /**
     * Is product archived ?.
     */
    #[
        Assert\NotNull,
        Assert\Type("boolean"),
        Serializer\SerializedName("is_archived"),
        // Sellsy accepts is_archived on PUT: the normalized Active flag needs it
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE, SplGroups::LIST)),
        SPL\Field(type: SplFields::BOOL, desc: "Product is archived", group: "Meta"),
        SPL\IsReadOnly,
        SPL\IsNotTested
    ]
    public bool $isArchived = false;

    /**
     * Product Active Flag.
     *
     * Splash normalized flag: Sellsy only knows archived products, so the
     * active state is the mirror of it.
     */
    #[
        Serializer\Ignore,
        SPL\Template(ProductFields::ACTIVE),
        SPL\Accessor(getter: "isActive", setter: "setActive"),
    ]
    public bool $isActive = true;

    /**
     * Is product declined ?.
     */
    #[
        Assert\Type("boolean"),
        Serializer\SerializedName("is_declined"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::LIST)),
        SPL\Field(type: SplFields::BOOL, desc: "Product is declined", group: "Meta"),
        SPL\IsReadOnly()
    ]
    public bool $isDeclined = false;

    /**
     * Product is Active when not Archived
     */
    public function isActive(): bool
    {
        return !$this->isArchived;
    }

    /**
     * Update Sellsy Archived Flag from Splash Active Flag
     */
    public function setActive(bool $isActive): void
    {
        $this->isArchived = !$isActive;
    }
}
