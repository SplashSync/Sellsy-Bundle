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

use Splash\Connectors\Sellsy\Models\Metadata\Invoice\PublicLink;

/**
 * Invoice Extras Fields
 */
trait ExtraInfosTrait
{
    /**
     * Invoice's fiscal year ID.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("fiscalYearId"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_INT, desc: "Fiscal Year ID"),
    ]
    public ?int $fiscalYearId = null;

    /**
     * Invoice's linked staff member ID.
     */
    #[
        Assert\NotNull,
        Assert\Type("integer"),
        JMS\SerializedName("assigned_staff_id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_INT, desc: "Fiscal Year"),
    ]
    public int $assignedStaffId = 0;

    /**
     * Invoice's linked contact ID.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("contact_id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_INT, desc: "Fiscal Year ID"),
    ]
    public ?int $contactId = null;

    /**
     * Invoice's Invoicing Address ID.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("invoicing_address_id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_INT, desc: "Invoicing Address ID"),
    ]
    public int $invoicingAddressId = 0;

    /**
     * Invoice's Delivery Address ID.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("delivery_address_id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_INT, desc: "Delivery Address ID"),
    ]
    public int $deliveryAddressId = 0;

    /**
     * Invoice's Rate Category ID.
     * @var int
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("rate_category_id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_INT, desc: "Rate Category ID"),
    ]
    public int $rateCategoryId = 0;

    /**
     * Invoice's Subscription ID.
     *
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("subscription_id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_INT, desc: "Subscription ID"),
    ]
    public ?int $subscriptionId = null;
}
