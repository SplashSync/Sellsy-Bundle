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

namespace App\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Companies Main Fields for Sandbox
 */
trait MainTrait
{
    /**
     * Product's name
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
    ]
    public ?string $name = null;

    /**
     * Product's reference
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: false),
        Serializer\Groups("read"),
    ]
    public string $reference;

    /**
     * Product's reference price excluding taxes
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: false),
        Serializer\Groups("read"),
    ]
    public string $reference_price_taxes_exc;

    /**
     * Product's purchase amount
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: false),
        Serializer\Groups("read"),
    ]
    public string $purchase_amount;

    /**
     * Product's reference price excluding taxes
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: false),
        Serializer\Groups("read"),
    ]
    public string $reference_price_taxes_inc;

    /**
     * Is Reference Price Taxes Free
     */
    #[
        Assert\Type("boolean"),
        ORM\Column(nullable: false),
        Serializer\Groups("read"),
    ]
    public bool $is_reference_price_taxes_free = false;

    /**
     * Product's currency code
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
    ]
    public ?string $currency = null;

    /**
     * Product's standard quantity
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: false),
        Serializer\Groups("read"),
    ]
    public string $standard_quantity;

    /**
     * Product's description
     */
    #[
        Assert\Type("string"),
        ORM\Column(nullable: true),
        Serializer\Groups("read"),
    ]
    public ?string $description = null;

    /**
     * Is Name Included in Description
     */
    #[
        Assert\Type("boolean"),
        ORM\Column(nullable: false),
        Serializer\Groups("read"),
    ]
    public bool $is_name_included_in_description = false;
}
