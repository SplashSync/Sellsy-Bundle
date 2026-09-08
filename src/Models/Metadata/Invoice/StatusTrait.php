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

use Splash\Connectors\Sellsy\Dictionary\InvoiceStatus;
use Splash\Core\Client\Splash;
use Splash\Core\Dictionary\Objects\Invoice\Status as SplashStatus;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Splash\Templates\Invoice\InvoiceStatusFields;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait StatusTrait
{
    /**
     * Sellsy Invoice Status
     *
     * Read only on the Api side: Sellsy owns this value, and status changes
     * go through its own transition endpoints, never through the invoice PUT.
     *
     * Splash sees it through the standard Status field template, converted
     * by the accessors below, and restricted to the statuses Sellsy supports.
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("status"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::LIST)),
        SPL\Template(InvoiceStatusFields::STATUS),
        SPL\Choices(InvoiceStatus::CHOICES),
        SPL\Accessor(getter: "getSplashStatus", setter: "setSplashStatus"),
        SPL\Flags(listed: true),
        SPL\IsNotTested,
    ]
    public string $status;

    /**
     * Invoice Status, as read from Sellsy, before any local transition
     */
    private ?string $originalStatus = null;

    //====================================================================//
    // SPLASH STATUS ACCESSORS
    //====================================================================//

    /**
     * Get Invoice Status, as a Splash Status
     */
    public function getSplashStatus(): string
    {
        return InvoiceStatus::toSplash($this->status);
    }

    /**
     * Set Invoice Status from a Splash Status
     *
     * Only transitions Sellsy accepts are applied: a validated invoice that
     * already carries payments never goes back to draft or cancelled.
     *
     * @SuppressWarnings(CyclomaticComplexity)
     */
    public function setSplashStatus(?string $status): bool
    {
        //====================================================================//
        // Empty Status => Do Nothing
        if (!$status) {
            return false;
        }
        //====================================================================//
        // Invoice is Cancelled
        if (SplashStatus::isCanceled($status) && (InvoiceStatus::CANCELLED != $this->status)) {
            if ($this->isValidatedWithPayments()) {
                return false;
            }
            $this->originalStatus = null;
            $this->status = InvoiceStatus::CANCELLED;

            return true;
        }
        //====================================================================//
        // Invoice is Draft
        if (SplashStatus::isDraft($status) && (InvoiceStatus::DRAFT != $this->status)) {
            if ($this->isValidatedWithPayments()) {
                return false;
            }
            $this->originalStatus = null;
            $this->status = InvoiceStatus::DRAFT;

            return true;
        }
        //====================================================================//
        // Invoice is Validated
        if (SplashStatus::isValidated($status)) {
            if (InvoiceStatus::isValidated($this->status)) {
                return false;
            }
            $this->originalStatus = $this->status;
            $this->status = InvoiceStatus::IN_PROGRESS;

            return true;
        }

        return false;
    }

    //====================================================================//
    // STATUS BASED RULES
    //====================================================================//

    /**
     * Check if Document is Editable
     */
    public function allowDocumentUpdate(): bool
    {
        if ($this->isSentToAccounting) {
            return false;
        }

        return InvoiceStatus::isEditable($this->originalStatus ?? $this->status);
    }

    /**
     * Check if Payments are Editable
     */
    public function allowPaymentsUpdate(): bool
    {
        return InvoiceStatus::isValidated($this->status)
            && !$this->isSentToAccounting
        ;
    }

    //====================================================================//
    // PRIVATE METHODS
    //====================================================================//

    /**
     * Check if Invoice is Validated and already carries Payments
     */
    private function isValidatedWithPayments(): bool
    {
        if (InvoiceStatus::isValidated($this->status) && $this->hasRegisteredPayments()) {
            return Splash::log()
                ->war("Invoice is Validated with Payments => Status unchanged.")
            ;
        }

        return false;
    }
}
