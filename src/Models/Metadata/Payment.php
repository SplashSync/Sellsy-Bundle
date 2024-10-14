<?php

namespace Splash\Connectors\Sellsy\Models\Metadata;

use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\Sellsy\Models\Metadata\Common\RowsAwareTrait;
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
    ]
    public ?string $number = null;

    #[
        Assert\Type("datetime"),
        JMS\SerializedName("paid_at"),
        JMS\Type("DateTime"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_DATETIME, desc: "Payment date (ISO 8601)"),
        SPL\Microdata("http://schema.org/PaymentChargeSpecification", "validFrom"),
    ]
    public \DateTime $paidAt;

    #[
        Assert\Type("string"),
        JMS\SerializedName("status"),
        JMS\Type("string"),
        SPL\Field(desc: "Payment status"),
        SPL\IsReadOnly,
    ]
    public ?string $status = null;

    #[
        Assert\NotNull,
        Assert\Type("integer"),
        JMS\SerializedName("payment_method_id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Payment method id (cf get./payments/methods)"),
        SPL\Microdata("http://schema.org/Invoice", "PaymentMethod"),
    ]
    public int $paymentMethodId;

    /**
     * Payment currency.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("currency"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_CURRENCY, desc: "Payment Currency Code"),
    ]
    public string $currency = "EUR";

    /**
     * Payment note.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("note"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_TEXT, desc: "Invoice Note"),
    ]
    protected ?string $note = null;
}