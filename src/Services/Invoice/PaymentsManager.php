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

namespace Splash\Connectors\Sellsy\Services\Invoice;

use Splash\Connectors\Sellsy\Models\Connector\SellsyApiV1AwareTrait;
use Splash\Connectors\Sellsy\Models\Connector\SellsyConnectorAwareTrait;
use Splash\Connectors\Sellsy\Models\Metadata\Invoice;
use Splash\Connectors\Sellsy\Models\Metadata\Payment;
use Webmozart\Assert\Assert;

/**
 * Manager CRUD of V1 Payments
 */
class PaymentsManager
{
    use SellsyConnectorAwareTrait;
    use SellsyApiV1AwareTrait;

    /**
     * Load Invoice Linked Payments
     *
     * @return Payment[]
     */
    public function fetchPayments(string $objectId): array
    {
        //====================================================================//
        // Fetch RAW List of Invoice Payments
        $rawList = $this->connector->getConnexion()->get("/invoices/".$objectId."/payments?limit=100");
        if (!$rawList) {
            return array();
        }
        //====================================================================//
        // Hydrate Invoice Payments
        /** @var Payment[] $payments */
        $payments = $this->connector->getHydrator()->hydrateMany($rawList['data'] ?? array(), Payment::class);
        Assert::allIsInstanceOf($payments, Payment::class);

        //====================================================================//
        // Walk on Received Payments
        $methodsManager = $this->connector->getLocator()->getPaymentMethodsManager();
        foreach ($payments as $payment) {
            //====================================================================//
            // Detect Payment Method Name
            $payment->method = $methodsManager->getTranslatedLabel($payment->paymentMethodId);
        }

        return $payments;
    }

    /**
     * Update Invoice Linked Payments using API V1
     */
    public function updatePayments(Invoice $invoice): bool
    {
        $success = true;
        $methodsManager = $this->connector->getLocator()->getPaymentMethodsManager();
        //====================================================================//
        // Walk on Invoice Payments
        foreach ($invoice->payments as $payment) {
            //====================================================================//
            // Update Payment Method Id
            $payment->setPaymentMethodId(
                $methodsManager->getIdFromLabel($payment->method)
            );
            //====================================================================//
            // Delete Updated Payment
            if ($payment->isToDelete()) {
                $success = $success && $this->deletePayment($invoice, $payment);
            }
            //====================================================================//
            // Recreate Updated Payment
            if ($payment->isToCreate()) {
                $success = $success && $this->createPayment($invoice, $payment);
            }
        }

        return $success;
    }

    /**
     * Create Invoice Linked Payments using API V1
     */
    private function createPayment(Invoice $invoice, Payment $payment): bool
    {
        //====================================================================//
        // Prepare API V1 Request
        $request = array(
            'method' => 'Document.createPayment',
            'params' => array(
                'payment' => array(
                    'date' => $payment->paidAt->getTimestamp(),
                    'amount' => $payment->getAmount(),
                    'medium' => $payment->paymentMethodId,
                    'ident' => $payment->number,
                    'notes' => $payment->note,
                    'doctype' => "invoice",
                    'docid' => $invoice->id,
                )
            )
        );
        //====================================================================//
        // Execute API V1 Request
        $response = $this->executeApiV1Request($this->connector, $request);

        return is_array($response);
    }

    /**
     * Delete Invoice Linked Payments using API V1
     */
    private function deletePayment(Invoice $invoice, Payment $payment): bool
    {
        //====================================================================//
        // Prepare API V1 Request
        $request = array(
            'method' => 'Document.deletePayment',
            'params' => array(
                'payment' => array(
                    'payid' => $payment->id,
                    'doctype' => "invoice",
                    'docid' => $invoice->id,
                )
            )
        );
        //====================================================================//
        // Execute API V1 Request
        $response = $this->executeApiV1Request($this->connector, $request);

        return is_array($response);
    }
}
