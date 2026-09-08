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
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

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
        Serializer\SerializedName("fiscalYearId"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::INT, desc: "Fiscal Year ID"),
    ]
    public ?int $fiscalYearId = null;

    /**
     * Invoice's linked staff member ID.
     */
    #[
        Assert\NotNull,
        Assert\Type("integer"),
        Serializer\SerializedName("assigned_staff_id"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::INT, desc: "Fiscal Year"),
    ]
    public int $assignedStaffId = 0;

    /**
     * Invoice's linked contact ID.
     */
    #[
        Assert\Type("integer"),
        Serializer\SerializedName("contact_id"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::INT, desc: "Fiscal Year ID"),
    ]
    public ?int $contactId = null;

    /**
     * Invoice's Invoicing Address ID.
     */
    #[
        Assert\Type("integer"),
        Serializer\SerializedName("invoicing_address_id"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::INT, desc: "Invoicing Address ID"),
    ]
    public int $invoicingAddressId = 0;

    /**
     * Invoice's Delivery Address ID.
     */
    #[
        Assert\Type("integer"),
        Serializer\SerializedName("delivery_address_id"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::INT, desc: "Delivery Address ID"),
    ]
    public int $deliveryAddressId = 0;

    /**
     * Invoice's Rate Category ID.
     *
     * @var int
     */
    #[
        Assert\Type("integer"),
        Serializer\SerializedName("rate_category_id"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::INT, desc: "Rate Category ID"),
    ]
    public int $rateCategoryId = 0;

    /**
     * Invoice's Subscription ID.
     */
    #[
        Assert\Type("integer"),
        Serializer\SerializedName("subscription_id"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::INT, desc: "Subscription ID"),
    ]
    public ?int $subscriptionId = null;
}
