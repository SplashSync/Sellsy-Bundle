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

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Company Metadata Fields
 */
trait MetadataTrait
{
    #[
        Assert\Type("datetime"),
        JMS\SerializedName("created"),
        JMS\Type("DateTime"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_DATETIME, desc: "Company creation date", group: "Meta"),
        SPL\IsReadOnly,
    ]
    public DateTime $created;

    /**
     * Invoice's isDeposit flag.
     */
    #[
        Assert\NotNull,
        Assert\Type("bool"),
        JMS\SerializedName("isDeposit"),
        JMS\Type("bool"),
        SPL\Field(type: SPL_T_BOOL, desc: "Is a Deposit Invoice ?", group: "Meta"),
        SPL\IsReadOnly,
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
        SPL\Field(type: SPL_T_BOOL, desc: "Is Invoice Sent to Accounting ?", group: "Meta"),
        SPL\IsReadOnly,
    ]
    public bool $isSentToAccounting = false;
}
