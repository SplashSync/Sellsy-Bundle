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

namespace Splash\Connectors\Sellsy\Services;

use Splash\Client\Splash;
use Splash\Connectors\Sellsy\Models\Connector\SellsyConnectorAwareTrait;
use Splash\Connectors\Sellsy\Models\Metadata\Contact;
use Splash\Connectors\Sellsy\Models\Metadata\Contact\CompanyLink;

class ContactCompaniesManager
{
    use SellsyConnectorAwareTrait;

    /**
     * Gte List of All Contact attached Companies
     *
     * @return CompanyLink[]
     */
    public function getAll(Contact $contact): array
    {
        //====================================================================//
        // Contact Companies Already Loaded
        if (is_array($contact->companiesLinks)) {
            return $contact->companiesLinks;
        }
        $contact->companiesLinks = array();
        //====================================================================//
        // Init Contact Companies
        $compagnies = $this->connector->getConnexion()->get(sprintf("/contacts/%s/companies", $contact->id));
        if (!is_array($compagnies['data'] ?? null) || empty($compagnies['data'])) {
            return $contact->companiesLinks;
        }
        //====================================================================//
        // Populate Contact Companies
        foreach ($compagnies['data'] as $company) {
            $contact->companiesLinks[] = new CompanyLink($contact, $company);
        }

        return $contact->companiesLinks;
    }

    /**
     * Get Contact First Company Link
     */
    public function getFirst(Contact $contact): ?CompanyLink
    {
        //====================================================================//
        // Get Contact Companies Links
        $links = $this->getAll($contact);
        if (empty($links)) {
            return null;
        }
        //====================================================================//
        // Get First Where Main
        foreach ($links as $link) {
            if ($link->isMain()) {
                return $link;
            }
        }

        //====================================================================//
        // Get First if Never Main
        return array_shift($links);
    }

    /**
     * Check if Contact is Attached to Company
     */
    public function hasCompany(Contact $contact, ?string $companyId): bool
    {
        //====================================================================//
        // Populate Contact Companies
        foreach ($this->getAll($contact) as $companyLinks) {
            if ($companyLinks->getCompanyId() == $companyId) {
                return true;
            }
        }

        return false;
    }

    /**
     * Attach Contact to Company
     */
    public function attachToCompany(Contact $contact, ?int $companyId): bool
    {
        //====================================================================//
        // Safety Check
        if (!$companyId) {
            return false;
        }
        //====================================================================//
        // Send Request
        $this->connector->getConnexion()->post(
            sprintf("/companies/%d/contacts/%d", $companyId, $contact->id),
            array()
        );
        //====================================================================//
        // Check response
        if ($this->connector->getConnexion()->getLastResponse()?->hasErrors()) {
            return Splash::log()->errTrace("Unable to attach Contact to Company.");
        }

        return true;
    }

    /**
     * Detach Contact from All Companies
     */
    public function detachFromAll(Contact $contact): bool
    {
        foreach ($this->getAll($contact) as $companyLink) {
            if (!$this->detachFromCompany($contact, $companyLink->getCompanyId())) {
                return false;
            }
        }

        return true;
    }

    /**
     * Detach Contact from Company
     */
    public function detachFromCompany(Contact $contact, ?int $companyId): bool
    {
        //====================================================================//
        // Safety Check
        if (!$companyId) {
            return false;
        }
        //====================================================================//
        // Send Request
        $this->connector->getConnexion()->delete(
            sprintf("/companies/%d/contacts/%d", $companyId, $contact->id),
        );
        //====================================================================//
        // Check response
        if ($this->connector->getConnexion()->getLastResponse()?->hasErrors()) {
            return Splash::log()->errTrace("Unable to detach Contact from Company.");
        }

        return true;
    }
}
