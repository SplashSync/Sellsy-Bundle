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

namespace App\Entity\Common\Rows\Traits;

use App\Entity\Common\Rows\Models\Discount;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * Sellsy Row Item Discount
 */

trait DiscountTrait
{
    /**
     * Discount Type
     */
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $discountType = null;

    /**
     * Discount Value
     */
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $discountValue = null;

    /**
     * Row's Discount Storage
     */
    #[
        Serializer\Groups(array("read", "write")),
    ]
    private ?Discount $discount = null;

    /**
     * Set Item Discount
     */
    public function setDiscount(?array $discount): void
    {
        if (empty($discount)) {
            $this->discount = $this->discountType = $this->discountValue = null;

            return;
        }

        $this->discountType = (string) $discount["type"] ?? null;
        $this->discountValue = (string) $discount["value"] ?? null;
    }

    /**
     * Get Item Discount
     */
    public function getDiscount(): ?Discount
    {
        if ($this->discountType && $this->discountValue) {
            $discount = new Discount();
            $discount->type = $this->discountType;
            $discount->value = $this->discountValue;

            return $discount;
        }

        return null;
    }
}
