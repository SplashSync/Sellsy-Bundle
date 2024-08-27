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
     * Invoice's date.
     */
    #[
        Assert\NotNull,
        Assert\Type("string<date>"),
        JMS\SerializedName("date"),
        JMS\Type("string<date>"),
        SPL\Field(type: SPL_T_DATE, desc: "Date of the invoice"),
    ]
    public string $date = "";

    /**
     * Invoice's shipping date.
     */
    #[
        Assert\NotNull,
        Assert\Type("string<string>"),
        JMS\SerializedName("shipping_date"),
        JMS\Type("string<date>"),
        SPL\Field(type: SPL_T_DATE, desc: "Shipping Date of the invoice"),
    ]
    public string $shippingDate = "";

    /**
     * Invoice's due date.
     */
    #[
        Assert\NotNull,
        Assert\Type("date"),
        JMS\SerializedName("due_date"),
        JMS\Type("string<date>"),
        SPL\Field(type: SPL_T_DATE, desc: "Due Date of the invoice"),
    ]
    public string $dueDate = "";

    /**
     * Invoice's subject.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("subject"),
        JMS\Type("string"),
        SPL\Field(desc: "Invoice Subject"),
    ]
    public string $subject = "";

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
    public string $currency = "";

    /**
     * Invoice's PDF link.
     */
    #[
        Assert\Type("url"),
        JMS\SerializedName("pdf_link"),
        JMS\Type("url"),
        SPL\Field(type: SPL_T_URL, desc: "Invoice PDF Link"),
    ]
    public ?string $pdfLink = null;

    /**
     * Invoice's order reference.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("order_reference"),
        JMS\Type("string"),
        SPL\Field(desc: "Invoice Order Reference"),
    ]
    public string $orderReference = "";

    /**
     * Invoice's isDeposit flag.
     */
    #[
        Assert\NotNull,
        Assert\Type("bool"),
        JMS\SerializedName("isDeposit"),
        JMS\Type("bool"),
        SPL\Field(type: SPL_T_BOOL, desc: "Is a Deposit Invoice ?"),
    ]
    public bool $isDeposit = false;

    /**
     * Invoice's isSentToAccounting flag.
     */
    #[
        Assert\NotNull,
        Assert\Type("bool"),
        JMS\SerializedName("is_sent_to_accounting"),
        JMS\Type("bool"),
        SPL\Field(type: SPL_T_BOOL, desc: "Is Invoice Sent to Accounting ?"),
    ]
    public bool $isSentToAccounting = false;

    /**
     * Invoice's note.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("note"),
        JMS\Type("string"),
        SPL\Field(desc: "Invoice Note"),
    ]
    public string $note = "";
}
