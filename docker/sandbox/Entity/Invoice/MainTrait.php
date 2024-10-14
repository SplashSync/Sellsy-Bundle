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
     * Invoice's Date
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(),
        Serializer\Groups("read")
    ]
    public string $date;

    /**
     * Invoice's Due Date
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(),
        Serializer\Groups("read")
    ]
    public string $due_date;

    /**
     * Invoice's Shipping Date
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(),
        Serializer\Groups("read")
    ]
    public string $shipping_date;

    /**
     * Invoice's Subject
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(),
        Serializer\Groups("read")
    ]
    public string $subject;

    /**
     * Invoice's Currency Code
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(),
        Serializer\Groups("read")
    ]
    public string $currency;

    /**
     * Invoice's Taxes
     */
    #[
        Assert\NotNull,
        Assert\Type("array"),
        ORM\Column(type: Types::JSON),
        Serializer\Groups("read")
    ]
    public array $taxes = array(
        "label" => "",
        "id" => 0,
        "rate" => "",
        "amount" => ""
    );

    /**
     * Invoice's Discount
     */
    #[
        Assert\NotNull,
        Assert\Type("array"),
        Assert\All(
            new Assert\Collection(
                fields: array(
                    "percent" => new Assert\Type("string"),
                    "amount" => new Assert\Type("string"),
                    "type" => new Assert\Choice(array("percent", "amount"))
                )
            )
        ),
        ORM\Column(type: Types::JSON),
        Serializer\Groups("read")
    ]
    public array $discount = array(
        "percent" => "",
        "amount" => "",
        "type" => ""
    );

    /**
     * Invoice's Linked Objects
     */
    #[
        Assert\NotNull,
        Assert\Type("array"),
        Assert\All(
            new Assert\Collection(
                fields: array(
                    "id" => new Assert\Type("integer"),
                    "type" => new Assert\Choice(array("company", "individual", "contact", "opportunity"))
                )
            )
        ),
        ORM\Column(type: Types::JSON),
        Serializer\Groups("read")
    ]
    public array $related = array(
        "id" => 0,
        "type" => ""
    );

    /**
     * Invoice's Owner
     */
    #[
        Assert\NotNull,
        Assert\Type("array"),
        ORM\Column(type: Types::JSON),
        Serializer\Groups("read")
    ]
    public array $owner = array(
        "id" => 0,
        "type" => ""
    );

    /**
     * Invoice's PDF Link
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(),
        Serializer\Groups("read")
    ]
    public string $pdf_link;

    /**
     * Invoice's Note
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(),
        Serializer\Groups("read")
    ]
    public string $note;
}
