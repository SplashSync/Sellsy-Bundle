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

namespace Splash\Connectors\Sellsy\Models\Metadata\Order;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Oder Main Information Fields
 */
trait MainTrait
{
    /**
     * Order's reference number.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("number"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Order Number"),
    ]
    public string $number;

    /**
     * Order's status.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("status"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Order Status"),
        SPL\Choices(array(
            "draft" => "Draft",
            "sent" => "Sent",
            "read" => "Read",
            "accepted" => "Accepted",
            "expired" => "Expired",
            "advanced" => "Advanced",
            "invoiced" => "Invoiced",
            "partialinvoiced" => "Partially Invoiced",
            "cancelled" => "Cancelled"
        )),
    ]
    public string $status;

    /**
     * Order's status.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("orderStatus"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Order Status"),
        SPL\Choices(array(
            "none" => "None",
            "wait" => "Wait",
            "picking" => "Picking",
            "sent" => "Sent",
            "partialsent" => "Partially Sent",
        )),
    ]
    public string $orderStatus;

    /**
     * Order's date.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("date"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_DATE, desc: "Order Date"),
    ]
    public string $date;

    /**
     * Order's due date.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("due_date"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_DATE, desc: "Order Due Date"),
    ]
    public string $dueDate;

    /**
     * Order's subject.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("subject"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_VARCHAR, desc: "Order Subject"),
    ]
    public string $subject;

    public object $amounts; //TODO: Create Amounts Object

    /**
     * Order's currency.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("currency"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_CURRENCY, desc: "Order Currency"),
    ]
    public string $currency;

    public object $taxes; //TODO: Create Taxes Object

    public ?object $discount = null; //TODO: Create Discount Object

    public object $related; //TODO: Create Related Object

    public object $publicLink; //TODO: Create PublicLink Object

    public object $paymentConditionAcceptance; //TODO: Create PaymentConditionAcceptance Object

    public object $owner; //TODO: Create Owner Object

    /**
     * Order's fiscal year ID.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("fiscal_year_id"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_INT, desc: "ID of the order's fiscal year"),
    ]
    public ?int $fiscalYearId = null;

    /**
     * Link to the Order's PDF.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("pdf_link"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_URL, desc: "Link to the PDF of the order"),
    ]
    public ?string $pdfLink = null;

    public object $decimalNumber; //TODO: Create DecimalNumber Object

    public ?object $serviceDates = null; //TODO: Create ServiceDates Object

    /**
     * A note about the order.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("note"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_TEXT, desc: "Order Note"),
    ]
    public string $note;

    /**
     * Order's shipping date.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("shipping_date"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_DATE, desc: "Shipping Date"),
    ]
    public ?string $shippingDate = null;
}
