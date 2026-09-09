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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Scopes granted to the Sandbox Api Client
 *
 * Sellsy answers a plain array of strings here, with no data wrapper: it is
 * the list of scopes the account actually granted to the application.
 */
class ScopesController extends AbstractController
{
    /**
     * Scopes granted to the Sandbox Application
     *
     * Every scope the connector requires, so that the whole Api is reachable.
     */
    private const GRANTED = array(
        //====================================================================//
        // Third Parties
        "companies.read", "companies.write",
        "contacts.read", "contacts.write",
        //====================================================================//
        // Catalog
        "items.read", "items.write",
        //====================================================================//
        // Sales Documents
        "invoices.read", "invoices.write",
        "orders.read", "orders.write",
        "taxes.read",
        "payments.read", "payments.write",
        //====================================================================//
        // Api Account
        "scopes.read",
        "webhooks.read", "webhooks.write",
    );

    #[Route("/scopes", name: "sellsy_scopes", methods: array("GET"))]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(self::GRANTED);
    }
}
