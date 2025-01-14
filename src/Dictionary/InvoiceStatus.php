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

namespace Splash\Connectors\Sellsy\Dictionary;

use Splash\Models\Objects\Invoice\Status;

class InvoiceStatus
{
    /**
     * Invoice is Draft => Rows are éditable
     */
    const DRAFT = "draft";

    /**
     * Invoice is Due => Rows are NOT éditable, but Payment May be Registered
     */
    const DUE = "due";

    /**
     * Invoice is Due => Rows are NOT éditable, but Payment May be Registered
     */
    const IN_PROGRESS = "payinprogress";

    /**
     * Invoice is Completed => Rows are NOT éditable, but Payment May be Registered
     */
    const PAID = "paid";

    /**
     * Invoice is Late => Rows are NOT éditable, but Payment May be Registered
     */
    const LATE = "late";

    /**
     * Invoice is Cancel => Rows are NOT éditable, Payment NOT éditable
     */
    const CANCELLED = "cancelled";

    const CHOICES = array(
        Status::DRAFT => "Draft",
        Status::PAYMENT_DUE => "Payment Due",
        Status::COMPLETE => "Paid",
        Status::CANCELED => "Cancelled",
    );

    /**
     * Check if Invoice Status Allow Edition
     */
    public static function isEditable(string $status): bool
    {
        return in_array($status, array(
            self::DRAFT,
            self::CANCELLED,
        ), true);
    }

    /**
     * Check if Invoice Status is Validated
     */
    public static function isValidated(string $status): bool
    {
        return in_array($status, array(
            self::DUE,
            self::IN_PROGRESS,
            self::PAID,
            self::LATE,
        ), true);
    }

    /**
     * Convert Invoice Status to Splash Status
     */
    public static function toSplash(string $status): string
    {
        return match ($status) {
            self::DRAFT => Status::DRAFT,
            self::PAID => Status::COMPLETE,
            self::CANCELLED => Status::CANCELED,
            default => Status::PAYMENT_DUE,
        };
    }
}
