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

namespace Splash\Connectors\Sellsy\Models\Metadata;

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\Sellsy\Models\Metadata\Payment\Amount;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Api Metadata Model for Sellsy Payments.
 */
class Payment
{
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("id"),
        JMS\Groups(array("Read")),
        JMS\Type("string"),
    ]
    public string $id;

    #[
        Assert\Type("string"),
        JMS\SerializedName("number"),
        JMS\Type("string"),
        SPL\Field(desc: "Transaction Number"),
        SPL\Microdata("http://schema.org/Invoice", "paymentMethodId"),
        SPL\Associations(array("number@payments", "amount@payments", "paidAt@payments")),
    ]
    public ?string $number = null;

    #[
        Assert\Type("array"),
        JMS\SerializedName("amount"),
        JMS\Type(Amount::class),
        SPL\Field(type: SPL_T_DOUBLE, desc: "Payment Amount"),
        SPL\Microdata("http://schema.org/PaymentChargeSpecification", "price"),
        SPL\Associations(array("number@payments", "amount@payments", "paidAt@payments")),
    ]
    public ?Amount $amount = null;

    #[
        Assert\Type("datetime"),
        JMS\SerializedName("paid_at"),
        JMS\Type("DateTime"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_DATE, desc: "Payment date (ISO 8601)"),
        SPL\Microdata("http://schema.org/PaymentChargeSpecification", "validFrom"),
        SPL\Associations(array("number@payments", "amount@payments", "paidAt@payments")),
    ]
    public DateTime $paidAt;

    #[
        Assert\NotNull,
        Assert\Type("integer"),
        JMS\SerializedName("payment_method_id"),
        JMS\Type("integer"),
        SPL\Field(
            type: SPL_T_VARCHAR,
            name: "Method ID",
            desc: "Sellsy Payment Method Id"
        ),
        SPL\IsReadOnly
    ]
    public ?int $paymentMethodId = null;

    #[
        JMS\Exclude(),
        SPL\Field(
            type: SPL_T_VARCHAR,
            name: "Method",
            desc: "Payment Method Code / Name"
        ),
        SPL\Microdata("http://schema.org/Invoice", "PaymentMethod"),
        SPL\IsNotTested
    ]
    public ?string $method = null;

    /**
     * Payment currency.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("currency"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_CURRENCY, desc: "Payment Currency Code"),
        SPL\IsNotTested
    ]
    public ?string $currency = "EUR";

    //====================================================================//
    // Read Only Informations
    //====================================================================//

    #[
        Assert\Type("string"),
        JMS\SerializedName("status"),
        JMS\Type("string"),
        SPL\Field(desc: "Payment status"),
        SPL\IsReadOnly,
    ]
    public ?string $status = null;

    /**
     * Payment note.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("note"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_TEXT, desc: "Invoice Note"),
        SPL\IsReadOnly
    ]
    public ?string $note = null;

    /**
     * Indicate this Payment was updated
     */
    private bool $updated = false;

    //====================================================================//
    // State Checkers
    //====================================================================//

    #[JMS\PostDeserialize]
    public function postDeserialize(): void
    {
        $this->updated = false;
    }

    /**
     * This Payment needs to be Created
     */
    public function isToCreate(): bool
    {
        return $this->updated;
    }

    /**
     * This Payment needs to be Deleted before Creation
     *
     * @return bool
     */
    public function isToDelete(): bool
    {
        return
            // Payment was Updated
            (!empty($this->id) && $this->updated)
            // Payment needs to be Deleted
            || (SPL_A_DELETE == $this->status)
        ;
    }

    //====================================================================//
    // Getters & Setters
    //====================================================================//

    /**
     * Update Number with Change Detection
     */
    public function setNumber(string $number): static
    {
        if ($number && ($number != $this->number)) {
            $this->number = $number;
            $this->updated = true;
        }

        return $this;
    }

    /**
     * Update Payment Method ID with Change Detection
     */
    public function setPaymentMethodId(?int $methodId): static
    {
        if ($methodId && $methodId != $this->paymentMethodId) {
            $this->paymentMethodId = $methodId;
            $this->updated = true;
        }

        return $this;
    }

    /**
     * Extract Payment Amount from Amount Object
     */
    public function getAmount(): float
    {
        return $this->amount ? (float) $this->amount->value : 0.0;
    }

    /**
     * Update Amount object with Payment Amount
     */
    public function setAmount(float $amount): static
    {
        if (abs($amount - $this->getAmount()) > 1E-3) {
            $this->amount ??= new Amount();
            $this->amount->value = (string) $amount;
            $this->updated = true;
        }

        return $this;
    }

    /**
     * Update Amount object with Payment Currency
     */
    public function setPaidAt(?DateTime $paidAt): static
    {
        if ($paidAt && ($paidAt != ($this->paidAt ?? null))) {
            $this->paidAt = $paidAt;
            $this->updated = true;
        }

        return $this;
    }

    /**
     * Extract Payment Currency from Amount Object
     */
    public function getCurrency(): string
    {
        return $this->amount?->currency ?? "EUR";
    }

    /**
     * Update Amount object with Payment Currency
     */
    public function setCurrency(?string $currency): static
    {
        if ($currency != $this->getCurrency()) {
            $this->amount ??= new Amount();
            $this->amount->currency = $currency ?? "EUR";
            $this->updated = true;
        }

        return $this;
    }
}
