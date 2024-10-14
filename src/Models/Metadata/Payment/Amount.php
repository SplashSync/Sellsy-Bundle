<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Payment;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sellsy Payment Amounts
 */
class Amount
{
    /**
     * Payment total amount.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total"),
        JMS\Type("string"),
    ]
    public string $value = "";

    /**
     * Payment remaining amount.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total"),
        JMS\Type("string"),
    ]
    public string $currency = "";
}