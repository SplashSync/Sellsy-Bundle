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

namespace App\Entity\Invoice;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait MainTrait
{
    /**
     * Invoice's Shipping Date
     */
    #[
        Assert\Type("string"),
        Assert\Regex(pattern: "/\\d{4}-\\d{2}-\\d{2}/"),
        ORM\Column(type: Types::STRING, nullable: true),
        Serializer\Groups("read")
    ]
    public ?string $shipping_date = null;

    /**
     * Invoice's Subject
     */
    #[
        Assert\Type("string"),
        ORM\Column(type: Types::STRING, length: 255, nullable: true),
        Serializer\Groups("read")
    ]
    public ?string $subject = null;

    /**
     * Invoice's Currency Code
     */
    #[
        Assert\Type("string"),
        Serializer\Groups("read"),
        ORM\Column(type: Types::STRING, length: 3),
    ]
    public ?string $currency = "EUR";

    /**
     * Invoice's Taxes
     */
    #[
        Assert\NotNull,
        Assert\Type("array"),
        ORM\Column(type: Types::JSON),
        Serializer\Groups("read")
    ]
    public array $taxes = array();

    /**
     * Invoice's Discount
     */
    #[
        Assert\Type("array"),
        Assert\Collection(
            fields: array(
                "percent" => new Assert\Type("string"),
                "amount" => new Assert\Type("string"),
                "type" => new Assert\Choice(array("percent", "amount"))
            )
        ),
        ORM\Column(type: Types::JSON, nullable: true),
        Serializer\Groups("read")
    ]
    public ?array $discount = null;

    /**
     * Invoice's Owner
     */
    #[
        Assert\NotNull,
        Assert\Type("array"),
        ORM\Column(type: Types::JSON),
        Serializer\Groups("read")
    ]
    public array $owner = array();

    /**
     * Invoice's PDF Link
     */
    #[
        Assert\Type("string"),
        Assert\Url,
        ORM\Column(type: Types::STRING, nullable: true),
        Serializer\Groups("read")
    ]
    public ?string $pdf_link = null;

    /**
     * Invoice's Note
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(type: Types::TEXT),
        Serializer\Groups("read")
    ]
    public string $note = "";
}
