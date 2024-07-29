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

use App\Entity\Contact;
use App\Entity\ContactAddress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Add a New Address to a Contact
 * This is not managed by API Platform up to now... ;-(
 */
class AddContactAddress extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function __invoke(Request $request, int $id): JsonResponse
    {
        //====================================================================//
        // Load Parent Order
        /** @var null|Contact $contact */
        $contact = $this->entityManager->getRepository(Contact::class)->find($id);
        if (!$contact) {
            throw new NotFoundHttpException();
        }
        //====================================================================//
        // Refresh Parent Product => Prevent Changes of Similar fields
        $this->entityManager->refresh($contact);
        //====================================================================//
        // Decode Received Product
        $rawData = json_decode($request->getContent(), true, 512, \JSON_BIGINT_AS_STRING);
        /** @var ContactAddress $address */
        $address = $this->serializer->denormalize($rawData, ContactAddress::class, "json");
        $address->setContact($contact);
        //====================================================================//
        // Persist Product
        $this->entityManager->persist($address);
        //====================================================================//
        // Save Product
        $this->entityManager->flush();

        return new JsonResponse($this->serializer->normalize($address, 'json', array(
            "resource_class" => ContactAddress::class,
            "operation_type" => "item"
        )));
    }
}
