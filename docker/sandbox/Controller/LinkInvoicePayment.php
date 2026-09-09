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

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Link or Unlink a Payment to an Invoice
 */
class LinkInvoicePayment extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function __invoke(Request $request, int $documentId, int $paymentId): JsonResponse
    {
        //====================================================================//
        // Load Invoice & Payment
        $invoice = $this->entityManager->getRepository(Invoice::class)->find($documentId);
        $payment = $this->entityManager->getRepository(Payment::class)->find($paymentId);
        if (!$invoice || !$payment) {
            throw new NotFoundHttpException();
        }
        //====================================================================//
        // Unlink the Payment from the Invoice
        if ($request->isMethod("DELETE")) {
            $payment->invoice = null;
            $this->entityManager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        //====================================================================//
        // Link the Payment to the Invoice
        $payment->invoice = $invoice;
        $this->entityManager->flush();

        return new JsonResponse($this->serializer->normalize($payment, 'json', array(
            "resource_class" => Payment::class,
            "operation_type" => "item"
        )));
    }
}
