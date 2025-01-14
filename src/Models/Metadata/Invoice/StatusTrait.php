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
use Splash\Client\Splash;
use Splash\Connectors\Sellsy\Dictionary\InvoiceStatus;
use Splash\Metadata\Attributes as SPL;
use Splash\Models\Objects\Invoice\Status as SplashStatus;
use Symfony\Component\Validator\Constraints as Assert;

trait StatusTrait
{
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("status"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "List", "Write")),
        SPL\Field(desc: "Third Party Id"),
        SPL\Flags(listed: true),
        SPL\Choices(InvoiceStatus::CHOICES),
        SPL\IsNotTested,
    ]
    public string $status;

    /**
     * Original Invoice Status
     */
    private ?string $originalStatus = null;

    /**
     * Get Invoice Splash Status
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function setStatus(?string $status): bool
    {
        //====================================================================//
        // Empty Status => Do Nothing
        if (!$status) {
            return false;
        }
        //====================================================================//
        // Invoice is Cancelled
        if (SplashStatus::isCanceled($status)) {
            if (InvoiceStatus::CANCELLED != $this->status) {
                if ($this->isValidatedWithPayments()) {
                    return false;
                }
                $this->originalStatus = null;
                $this->status = InvoiceStatus::CANCELLED;

                return true;
            }
        }
        //====================================================================//
        // Invoice is Draft
        if (SplashStatus::isDraft($status)) {
            if (InvoiceStatus::DRAFT != $this->status) {
                if ($this->isValidatedWithPayments()) {
                    return false;
                }
                $this->originalStatus = null;
                $this->status = InvoiceStatus::DRAFT;

                return true;
            }
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

    /**
     * Get Invoice Splash Status
     */
    public function getStatus(): string
    {
        return InvoiceStatus::toSplash($this->status);
    }

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

    /**
     * Check if Payments are Editable
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
