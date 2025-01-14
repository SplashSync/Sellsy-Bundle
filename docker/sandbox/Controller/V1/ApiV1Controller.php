<?php

namespace App\Controller\V1;

use App\Entity\Invoice;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

/**
 * Special Controller to Manage API V1 Actions
 */
#[AsController]
#[Route(
    path: '/v1/',
    name: 'sellsy_api_v1',
)]
class ApiV1Controller
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        try {
            //====================================================================//
            // Extract V1 Inputs
            $rawInputs = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            Assert::isArray($rawInputs);
            Assert::eq($rawInputs['io_mode'] ?? null, 'json');
            Assert::stringNotEmpty($rawInputs['do_in'] ?? null);
            Assert::isArray($inputs = json_decode($rawInputs['do_in'], true, 512, JSON_THROW_ON_ERROR));
            Assert::string($method = $inputs['method'] ?? null);
            Assert::isArray($params = $inputs['params'] ?? null);
            //====================================================================//
            // Route Inputs to Action
            return match($method) {
                "Document.createPayment" => $this->createPayment($params),
                "Document.deletePayment" => $this->deletePayment($params),
                default => new Response("OK", Response::HTTP_BAD_REQUEST),
            };
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * Register a New Payment
     *
     * @param array $params
     *
     * @return Response
     *
     * @throws \DateMalformedStringException
     */
    private function createPayment(array $params): Response
    {
        //====================================================================//
        // Validate Inputs
        Assert::isArray($payment = $params['payment'] ?? null);
        Assert::eq("invoice", $payment['doctype'] ?? null);
        Assert::positiveInteger($docId = (int)$payment['docid'] ?? null);
        Assert::nullOrStringNotEmpty($number = (string) $payment['ident'] ?? null);
        Assert::positiveInteger($paymentMethodId = (int)$payment['medium'] ?? null);
        Assert::positiveInteger($timestamp = (int) $payment['date'] ?? null);
        Assert::notEmpty($date = (new \DateTime())->setTimestamp($timestamp));
        Assert::numeric($amount = $payment['amount'] ?? null);

        //====================================================================//
        // Load Invoice
        if (!$invoice = $this->entityManager->find(Invoice::class, $docId)) {
            throw new NotFoundHttpException("Invoice not found");
        }

        //====================================================================//
        // Create Payment
        $entity = new Payment();
        $entity->invoice = $invoice;
        $entity->amount = $amount;
        $entity->number = $number;
        $entity->paymentMethodId = $paymentMethodId;
        $entity->paidAt = $date;

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return new JsonResponse(array("status" => "success"));
    }

    /**
     * Delete a Payment
     *
     * @param array $params
     *
     * @return Response
     */
    private function deletePayment(array $params): Response
    {
        //====================================================================//
        // Validate Inputs
        Assert::isArray($payment = $params['payment'] ?? null);
        Assert::eq("invoice", $payment['doctype'] ?? null);
        Assert::positiveInteger($paymentId = (int)$payment['payid'] ?? null);
        Assert::positiveInteger($docId = (int)$payment['docid'] ?? null);
        //====================================================================//
        // Load Payment
        if (!$payment = $this->entityManager->find(Payment::class, $paymentId)) {
            throw new NotFoundHttpException("Payment not found");
        }
        //====================================================================//
        // Delete Payment
        $this->entityManager->remove($payment);
        $this->entityManager->flush();

        return new JsonResponse(array("status" => "success"));
    }
}