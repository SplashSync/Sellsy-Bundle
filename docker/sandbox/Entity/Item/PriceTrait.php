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

namespace App\Entity\Item;

use App\Entity\Taxe;
use Doctrine\ORM\Mapping as ORM;
use Splash\Models\Objects\PricesTrait;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait PriceTrait
{
    use PricesTrait;

    /**
     * Product's wholesale price in Splash format
     */
    #[
        ORM\Column(nullable: true),
    ]
    public ?float $reference_price = null;

    /**
     * Product's reference price excluding taxes
     */
    #[
        ORM\Column(),
        Serializer\Groups("read"),
    ]
    public float $reference_price_taxes_exc = 0.0;

    /**
     * Product's reference price excluding taxes
     */
    #[
        ORM\Column(),
        Serializer\Groups("read"),
    ]
    public float $reference_price_taxes_inc = 0.0;

    /**
     * Is Reference Price Taxes Free
     */
    #[
        Assert\Type("boolean"),
        ORM\Column(),
    ]
    public bool $is_reference_price_taxes_free = true;

    /**
     * Product's purchase amount
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: false),
        Serializer\Groups("read"),
    ]
    public string $purchase_amount = "0.00";

    /**
     * Product's currency code
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
    ]
    public ?string $currency = "EUR";

    /**
     * Product's tax ID.
     */
    #[
        Assert\Type("integer"),
        ORM\Column(nullable: false),
        Serializer\Groups("read"),
    ]
    public int $tax_id = 0;

    private ?Taxe $tax = null;

    public function getSplashPrice(): ?array
    {
        return self::prices()->encode(
            $this->is_reference_price_taxes_free ? $this->reference_price : null,
            $this->isTaxCreated($this->tax_id) ? $this->tax->rate : 0.00,
            !$this->is_reference_price_taxes_free ? $this->reference_price : null,
            $this->currency ?: "EUR"
        );
    }

    public function isTaxCreated(int $tax_id): bool
    {
        if (null === $this->tax || 0 === $tax_id) {
            return false;
        }

        return true;
    }

    //    #[ORM\PreUpdate()]
    //    public function updatePriceFields(): static
    //    {
    ////        self::prices()->encode();
    //        $this->reference_price_taxes_exc = $this->is_reference_price_taxes_free
    //            ? $this->reference_price
    //            : $this->reference_price - $tva
    //        ;
    //
    //        return $this;
    //    }
}
