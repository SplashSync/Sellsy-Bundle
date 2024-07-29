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

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
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
        JMS\SerializedName("is_archived"),
        JMS\Type("boolean"),
        JMS\Groups(array("Read", "List")),
        SPL\Field(type: SPL_T_BOOL, desc: "Product is archived", group: "Meta"),
        SPL\Microdata("http://schema.org/Product", "offered"),
        SPL\IsNotTested
    ]
    public bool $isArchived = false;

    /**
     * Is product declined ?.
     */
    #[
        Assert\Type("boolean"),
        JMS\SerializedName("is_declined"),
        JMS\Type("boolean"),
        JMS\Groups(array("Read", "List")),
        SPL\Field(type: SPL_T_BOOL, desc: "Product is declined", group: "Meta"),
        SPL\IsReadOnly()
    ]
    public bool $isDeclined = false;
}
