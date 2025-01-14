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
use Splash\Connectors\Sellsy\Models\Metadata\Payment;
use Splash\Metadata\Attributes as SPL;
use Webmozart\Assert\Assert;

/**
 * Access to Invoice Payments
 */
trait PaymentsTrait
{
    /**
     * @var Payment[]
     */
    #[
        JMS\Groups(array("None")),
        SPL\ListResource(targetClass: Payment::class),
        SPL\Accessor(
            factory: "createPayment",
            remover: "removePayment",
        ),
    ]
    public array $payments = array();

    private int $originalPaymentsCount = 0;

    /**
     * Initialize Payments
     */
    public function loadPayments(array $payments): void
    {
        Assert::allIsInstanceOf($payments, Payment::class);

        $this->payments = $payments;
        $this->originalPaymentsCount = count($payments);
    }

    /**
     * Has Registered Payments
     */
    public function hasRegisteredPayments(): bool
    {
        return !empty($this->originalPaymentsCount);
    }

    /**
     * Create a New Payment Item
     */
    public function createPayment(): object
    {
        return new Payment();
    }

    /**
     * Mark Payment as Deleted
     */
    public function removePayment(Payment $payment): void
    {
        $payment->status = SPL_A_DELETE;
    }
}
