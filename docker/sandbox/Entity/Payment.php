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

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata as API;

/**
 * Class representing the Payment model.
 */
#[ORM\Entity]
#[API\ApiResource(
    uriTemplate: '/invoices/{id}/payments',
    operations: array(
        new API\GetCollection(),
        new API\Get(),
    ),
    uriVariables: array(
        'id' => new API\Link(fromProperty: 'payments', fromClass: Invoice::class),
    ),
)]
class Payment extends AbstractSellsyObject
{
    /**
     * Unique Identifier.
     */
    #[
        Assert\Type("integer"),
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: Types::INTEGER),
    ]
    public int $id;

    /**
     * Link to Parent Invoice
     */
    #[ORM\ManyToOne(targetEntity: Invoice::class, inversedBy: 'payments')]
    #[Serializer\Ignore()]
    public ?Invoice $invoice;

    #[
        Assert\Type("string"),
        ORM\Column(type: Types::STRING, nullable: true),
    ]
    public ?string $number = null;

    #[
        Assert\Type("string"),
        ORM\Column(type: Types::STRING),
        Serializer\SerializedPath("[amount][value]")
    ]
    public string $amount;

    /**
     * Payment currency.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(type: Types::STRING),
        Serializer\SerializedPath("[amount][currency]")
    ]
    public string $currency = "EUR";

    #[
        Assert\Type("datetime"),
        ORM\Column(type: Types::DATETIME_MUTABLE),
    ]
    public DateTime $paidAt;

    #[
        Assert\NotNull,
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER),
    ]
    public int $paymentMethodId;



    //====================================================================//
    // Read Only Informations
    //====================================================================//

    #[
        Serializer\SerializedPath("[amounts][remaining]")
    ]
    public string $remaining = "0.0";

    /**
     * Payment Status.
     */
    public ?string $status = "status";

    /**
     * Payment note.
     */
    public ?string $note = "notes...";

    #[
        Serializer\SerializedPath("[amounts][total]")
    ]
    public function getAmountsTotal(): string
    {
        return $this->amount;
    }
}
