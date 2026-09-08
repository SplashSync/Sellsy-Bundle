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

namespace Splash\Connectors\Sellsy\Services\Accounting;

use Splash\Connectors\Sellsy\Interfaces\SellsyConnectorAwareInterface;
use Splash\Connectors\Sellsy\Models\Connector\SellsyConnectorAwareTrait;
use Splash\Connectors\Sellsy\Models\Metadata\Invoice;
use Splash\Connectors\Sellsy\Models\Metadata\Payment;
use Splash\Core\Client\Splash;
use Splash\Core\Helpers\ObjectsHelper;

/**
 * Manage CRUD of Invoices Payments
 *
 * Sellsy has no payment endpoint on the document itself: a payment belongs to
 * a company, and is then linked to the invoice it settles.
 */
class PaymentsManager implements SellsyConnectorAwareInterface
{
    use SellsyConnectorAwareTrait;

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
        $payments = $this->connector->getHydrator()->hydrateMany($rawList['data'] ?? array(), Payment::class);

        //====================================================================//
        // Walk on Received Payments
        $methodsManager = $this->connector->getLocator()->getPaymentMethodsManager();
        foreach ($payments as $payment) {
            //====================================================================//
            // A freshly read Payment is not a modified one. JMS reset this flag
            // on PostDeserialize: Symfony Serializer has no such hook.
            $payment->postDeserialize();
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
     * Create Invoice Linked Payment
     */
    private function createPayment(Invoice $invoice, Payment $payment): bool
    {
        //====================================================================//
        // Safety Check - Customer is Known
        if (!$companyId = ObjectsHelper::id((string) $invoice->getCustomer())) {
            return Splash::log()->err("Unable to register a Payment: Invoice has no Customer.");
        }
        //====================================================================//
        // Create the Company Payment
        $created = $this->connector->getConnexion()->post(
            sprintf("/companies/%s/payments", $companyId),
            $this->connector->getHydrator()->extract($payment)
        );
        if (!$paymentId = $created["id"] ?? null) {
            return Splash::log()->err("Unable to register a Payment: Sellsy returned no Payment Id.");
        }
        //====================================================================//
        // Link the Payment to the Invoice
        // Sellsy expects a json number here, not a string
        $linked = $this->connector->getConnexion()->post(
            sprintf("/invoices/%s/payments/%s", $invoice->id, $paymentId),
            array("amount" => $payment->getSplashAmount())
        );
        if (!is_array($linked)) {
            //====================================================================//
            // Sellsy refused the link: the payment would stay on the customer
            // account, unrelated to any document.
            $this->connector->getConnexion()->delete(sprintf("/payments/%s", $paymentId));

            return false;
        }

        return true;
    }

    /**
     * Delete Invoice Linked Payment
     *
     * Sellsy refuses to delete a payment as long as it settles a document:
     * it is unlinked from the invoice first.
     */
    private function deletePayment(Invoice $invoice, Payment $payment): bool
    {
        //====================================================================//
        // Safety Check - Payment is Stored
        if (empty($payment->id)) {
            return true;
        }
        //====================================================================//
        // Unlink the Payment from the Invoice
        $this->connector->getConnexion()->delete(
            sprintf("/invoices/%s/payments/%s", $invoice->id, $payment->id)
        );

        return is_array($this->connector->getConnexion()->delete(
            sprintf("/payments/%s", $payment->id)
        ));
    }
}
