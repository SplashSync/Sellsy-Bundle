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

namespace Splash\Connectors\Sellsy\Models\Metadata\Common\Rows;

use JMS\Serializer\Annotation as JMS;
use Splash\Models\Helpers\ObjectsHelper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sellsy row related data
 */
class Related
{
    /**
     * Row Relation ID.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("id"),
        JMS\Type("integer"),
    ]
    public int $id;

    /**
     * Row Relation Type.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("type"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write")),
    ]
    public string $type;

    /**
     * Row Declination ID.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("declination_id"),
        JMS\Type("integer"),
    ]
    public ?int $declinationId = null;

    public function toSplash(): ?string
    {
        return ObjectsHelper::encode("Product", (string) $this->id);
    }

    public function fromSplash(?string $productId): static
    {
        $this->id = (int) ObjectsHelper::id((string) $productId);
        $this->type ??= "product";
        $this->declinationId = null;

        return $this;
    }
}
