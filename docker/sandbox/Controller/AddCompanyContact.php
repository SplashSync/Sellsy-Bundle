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

/**
 * Add a Contact to a Company
 */
class AddCompanyContact extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(Request $request, int $id, int $contactId): JsonResponse
    {
        //====================================================================//
        // Load Company
        /** @var null|Company $company */
        $company = $this->entityManager->getRepository(Company::class)->find($id);
        if (!$company) {
            throw new NotFoundHttpException();
        }
        //====================================================================//
        // Load Contact
        /** @var null|Contact $contact */
        $contact = $this->entityManager->getRepository(Contact::class)->find($contactId);
        if (!$contact) {
            throw new NotFoundHttpException();
        }
        //====================================================================//
        // Add Contact to Company
        $company->addContact($contact);
        //====================================================================//
        // Save Product
        $this->entityManager->flush();

        return new JsonResponse(array("roles" => array("main", "invoicing", "dunning")));
    }
}
