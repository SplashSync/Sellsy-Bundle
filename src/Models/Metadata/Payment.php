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
use Splash\Connectors\Sellsy\Dictionary\PaymentTypes;
use Splash\Connectors\Sellsy\Models\Metadata\Payment\Amount;
use Splash\Core\Dictionary\SplFields;
use Splash\Core\Dictionary\SplOperations;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Splash\Templates\Accounting\AccountingPaymentsFields;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Api Metadata Model for Sellsy Payments.
 */
class Payment
{
    #[
        Assert\Type("string"),
        Serializer\SerializedName("id"),
        Serializer\Groups(array(SplGroups::READ)),
    ]
    //====================================================================//
    // Nullable on purpose: a payment being created has no id yet.
    public ?string $id = null;

    /**
     * Payment Type.
     *
     * Sellsy books what a customer pays as a credit: Splash never registers
     * anything else on a sale document.
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("type"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE)),
    ]
    public string $type = PaymentTypes::CREDIT;

    #[
        Assert\Type("string"),
        Serializer\SerializedName("number"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(AccountingPaymentsFields::PAYMENT_NUMBER),
        SPL\Associations(array("number@payments", "amount@payments", "paidAt@payments")),
    ]
    public ?string $number = null;

    #[
        Assert\Type("array"),
        Serializer\SerializedName("amount"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Template(AccountingPaymentsFields::PAYMENT_AMOUNT),
        SPL\Accessor(getter: "getSplashAmount", setter: "setSplashAmount"),
        SPL\Associations(array("number@payments", "amount@payments", "paidAt@payments")),
    ]
    public ?Amount $amount = null;

    #[
        Assert\Type("datetime"),
        Serializer\SerializedName("paid_at"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE)),
        SPL\Template(AccountingPaymentsFields::PAYMENT_DATE),
        SPL\Associations(array("number@payments", "amount@payments", "paidAt@payments")),
    ]
    public DateTime $paidAt;

    #[
        Assert\NotNull,
        Assert\Type("integer"),
        Serializer\SerializedName("payment_method_id"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(
            type: SplFields::VARCHAR,
            name: "Method ID",
            desc: "Sellsy Payment Method Id"
        ),
        SPL\IsReadOnly
    ]
    public ?int $paymentMethodId = null;

    #[
        Serializer\Ignore,
        SPL\Template(AccountingPaymentsFields::PAYMENT_MODE),
        SPL\IsNotTested
    ]
    public ?string $method = null;

    /**
     * Payment currency.
     */
    #[
        Serializer\Ignore,
        SPL\Field(type: SplFields::CURRENCY, desc: "Payment Currency Code"),
        SPL\IsNotTested
    ]
    public ?string $currency = "EUR";

    //====================================================================//
    // Read Only Informations
    //====================================================================//

    #[
        Assert\Type("string"),
        Serializer\SerializedName("status"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Field(desc: "Payment status"),
        SPL\IsReadOnly,
    ]
    public ?string $status = null;

    /**
     * Payment note.
     */
    #[
        Assert\Type("string"),
        Serializer\SerializedName("note"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::TEXT, desc: "Invoice Note"),
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
            || (SplOperations::DELETE == $this->status)
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
     *
     * Named apart from the property: Symfony Serializer would use it to
     * normalize the amount, where Sellsy expects the whole object.
     */
    public function getSplashAmount(): float
    {
        return $this->amount ? (float) $this->amount->value : 0.0;
    }

    /**
     * Update Amount object with Payment Amount
     */
    public function setSplashAmount(float $amount): static
    {
        if (abs($amount - $this->getSplashAmount()) > 1E-3) {
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
        //====================================================================//
        // Sellsy stores a date time, Splash a day: comparing the raw objects
        // would mark every payment as updated on each sync.
        $current = isset($this->paidAt) ? $this->paidAt->format("Y-m-d") : null;
        if ($paidAt && ($paidAt->format("Y-m-d") !== $current)) {
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
        return $this->amount->currency ?? "EUR";
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
