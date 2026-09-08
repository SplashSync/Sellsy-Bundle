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

use Splash\Core\Helpers\ObjectsHelper;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
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
        Serializer\SerializedName("id"),
        Serializer\Groups(SplGroups::DEFAULT),
    ]
    public int $id;

    /**
     * Row Relation Type.
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("type"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE)),
    ]
    public string $type;

    /**
     * Row Declination ID.
     */
    #[
        Assert\Type("integer"),
        Serializer\SerializedName("declination_id"),
        Serializer\Groups(SplGroups::DEFAULT),
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
