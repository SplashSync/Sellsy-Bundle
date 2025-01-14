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

use ApiPlatform\Metadata as API;
use App\Entity\Payment;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Invoice links with Payments
 */
trait PaymentsTrait
{
    /**
     * Storage for Payments
     *
     * @var Collection<Payment>
     */
    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: Payment::class)]
    #[API\Link(toProperty: 'invoice')]
    protected Collection $payments;

    /**
     * Add Invoice Payment
     */
    public function addPayment(Payment $payment): static
    {
        $payment->invoice = $this;
        $this->payments->add($payment);

        return $this;
    }

    /**
     * Remove Invoice Payment
     */
    public function removePayment(Payment $payment): static
    {
        $this->payments->remove($payment);

        return $this;
    }

    /**
     * Get Invoice Payments
     */
    protected function getPayments(): Collection
    {
        return $this->payments;
    }
}
