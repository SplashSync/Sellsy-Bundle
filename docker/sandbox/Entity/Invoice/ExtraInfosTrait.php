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

namespace App\Entity\Invoice;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait ExtraInfosTrait
{
    /**
     * Invoice's Fiscal Year ID.
     */
    #[
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER),
        Serializer\Groups("read")
    ]
    public ?int $fiscalYearId = 0;

    /**
     * Invoice's Assigned Staff ID.
     */
    #[
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER),
        Serializer\Groups("read")
    ]
    public int $assignedStaffId = 0;

    /**
     * Invoice's Contact ID.
     */
    #[
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER),
        Serializer\Groups("read")
    ]
    public ?int $contactId = 0;

    /**
     * Invoice's Contact Invoicing ID.
     */
    #[
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER),
        Serializer\Groups("read")
    ]
    public int $invoicingAddressId = 0;

    /**
     * Invoice's Delivery Address ID.
     */
    #[
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER),
        Serializer\Groups("read")
    ]
    public int $deliveryAddressId = 0;

    /**
     * Invoice's Rate Category ID.
     */
    #[
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER),
        Serializer\Groups("read")
    ]
    public int $rateCategoryId = 0;

    /**
     * Invoice's Subscription ID.
     */
    #[
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER),
        Serializer\Groups("read")
    ]
    public int $subscriptionId = 0;
}
