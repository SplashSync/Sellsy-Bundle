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

namespace Splash\Connectors\Sellsy\Models\Metadata\Invoice;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Invoices Main Information Fields
 */
trait MainTrait
{
    /**
     * Invoice's currency.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("currency"),
        JMS\Type("string"),
        SPL\Field(desc: "Invoice Currency Code"),
    ]
    public string $currency = "EUR";

    /**
     * Invoice's subject.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("subject"),
        JMS\Type("string"),
        SPL\Field(desc: "Invoice Subject"),
    ]
    protected ?string $subject = null;

    //    /**
    //     * Invoice's order reference.
    //     */
    //    #[
    //        Assert\Type("string"),
    //        JMS\SerializedName("third_reference"),
    //        JMS\Type("string"),
    //        JMS\Groups(array("Write")),
    //        SPL\Field(desc: "Invoice client Reference"),
    //    ]
    //    public ?string $clientReference = "CUSTO-REf";

    /**
     * Invoice's order reference.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("order_reference"),
        JMS\Type("string"),
        SPL\Field(desc: "Invoice Order Reference"),
    ]
    protected ?string $orderReference = null;

    /**
     * Invoice's note.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("note"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_TEXT, desc: "Invoice Note"),
    ]
    protected ?string $note = null;

    /**
     * Get Subject as String
     */
    public function getSubject(): string
    {
        return (string) $this->subject;
    }

    /**
     * Set Subject
     */
    public function setSubject(?string $subject): static
    {
        $this->subject = (string) $subject;

        return $this;
    }

    /**
     * Get Order Reference as String
     */
    public function getOrderReference(): string
    {
        return (string) $this->orderReference;
    }

    /**
     * Set Order Reference
     */
    public function setOrderReference(?string $orderReference): static
    {
        $this->orderReference = (string) $orderReference;

        return $this;
    }

    /**
     * Get Note as String
     */
    public function getNote(): string
    {
        return (string) $this->note;
    }

    /**
     * Set Note
     */
    public function setNote(?string $note): static
    {
        $this->note = (string) $note;

        return $this;
    }
}
