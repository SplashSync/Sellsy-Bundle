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

use App\Entity\Company;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Record a New Payment on a Company
 *
 * Sellsy payments belong to a third party, and are linked to the documents
 * they settle afterwards.
 */
class CreateCompanyPayment extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function __invoke(Request $request, int $id): JsonResponse
    {
        //====================================================================//
        // Load Parent Company
        $company = $this->entityManager->getRepository(Company::class)->find($id);
        if (!$company) {
            throw new NotFoundHttpException();
        }
        //====================================================================//
        // Decode Received Payment
        $rawData = json_decode($request->getContent(), true, 512, \JSON_BIGINT_AS_STRING);
        /** @var Payment $payment */
        $payment = $this->serializer->denormalize($rawData, Payment::class, "json");
        $payment->company = $company;
        $payment->invoice = null;
        //====================================================================//
        // Save Payment
        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        return new JsonResponse($this->serializer->normalize($payment, 'json', array(
            "resource_class" => Payment::class,
            "operation_type" => "item"
        )));
    }
}
