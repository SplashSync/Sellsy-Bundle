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

use Splash\Core\Dictionary\SplFields;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Splash\Templates\InvoiceFields;
use Splash\Templates\OrderFields;
use Symfony\Component\Serializer\Attribute as Serializer;
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
        Serializer\SerializedName("currency"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(InvoiceFields::CURRENCY),
    ]
    public string $currency = "EUR";

    /**
     * Invoice's client reference.
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("company_reference"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(InvoiceFields::REF_CUSTOMER),
    ]
    public ?string $companyReference = null;

    /**
     * Invoice's subject.
     *
     * Sellsy refuses a document without subject, Splash does not ask for one:
     * the default value keeps creations working when none is written.
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("subject"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE, SplGroups::REQUIRED)),
        SPL\Field(desc: "Invoice Subject"),
    ]
    protected ?string $subject = "Your Invoice";

    /**
     * Invoice's order reference.
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("order_reference"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(OrderFields::REF_CUSTOMER),
    ]
    protected ?string $orderReference = null;

    /**
     * Invoice's note.
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("note"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::TEXT, desc: "Invoice Note"),
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
