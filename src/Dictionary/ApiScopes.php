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

namespace Splash\Connectors\Sellsy\Dictionary;

/**
 * Sellsy Api Scopes Dictionary
 *
 * Scopes the connector asks for at authorization: the account must grant them
 * all, or the related objects stay out of reach.
 */
class ApiScopes
{
    /**
     * Scopes required by the Connector, grouped by feature
     */
    const REQUIRED = array(
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
}
