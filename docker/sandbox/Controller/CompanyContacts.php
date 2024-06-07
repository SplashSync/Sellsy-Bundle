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
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Get List of Companies for a Contact
 */
class CompanyContacts extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function __invoke(Request $request, int $id): JsonResponse
    {
        //====================================================================//
        // Load Contact
        /** @var null|Contact $contact */
        $contact = $this->entityManager->getRepository(Contact::class)->find($id);
        if (!$contact) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(array("data" => $this->serializer->normalize($contact->getCompanies()->toArray(), 'json', array(
            'groups' => array('read', 'contacts'),
            "resource_class" => Company::class,
            "operation_type" => "collection"
        ))));
    }
}
