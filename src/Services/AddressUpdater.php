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
use Splash\Connectors\Sellsy\Models\Metadata\Address;
use Splash\Connectors\Sellsy\Models\Metadata\Company;
use Splash\Connectors\Sellsy\Models\Metadata\Contact;
use Splash\OpenApi\Hydrator\Hydrator;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;

/**
 * Manage Updates of Companies Addresses
 */
class AddressUpdater
{
    private ConnexionInterface $connexion;

    private Hydrator $hydrator;

    /**
     * Configure with Current API Connexion Settings
     */
    public function configure(ConnexionInterface $connexion, Hydrator $hydrator): static
    {
        $this->connexion = $connexion;
        $this->hydrator = $hydrator;

        return $this;
    }

    /**
     * Create or Update Invoicing Address
     */
    public function createOrUpdateInvoicingAddress(Company|Contact $parent): void
    {
        //====================================================================//
        // Safety Check - Address is Defined
        if (!$address = $parent->invoicingAddress) {
            return;
        }
        //====================================================================//
        // Ensure Name is Defined
        if (empty($address->name)) {
            $address->name = "Invoicing Address";
        }
        //====================================================================//
        // Force Invoicing Address Flag
        $address->isInvoicingAddress = true;
        //====================================================================//
        // Update Address
        $this->createOrUpdateAddress($parent, $address);
    }

    /**
     * Create or Update Delivery Address
     */
    public function createOrUpdateDeliveryAddress(Company|Contact $parent): void
    {
        //====================================================================//
        // Safety Check - Address is Defined
        if (!$address = $parent->deliveryAddress) {
            return;
        }
        //====================================================================//
        // Safety Check - Addresses are NOT Similar
        if ($this->isBothInvoicingAndDelivery($parent)) {
            Splash::log()->msg(
                "Invoicing and Delivery Address are similar, update of delivery address skipped"
            );

            return;
        }
        //====================================================================//
        // Ensure Name is Defined
        if (empty($address->name)) {
            $address->name = "Delivery Address";
        }
        //====================================================================//
        // Force Invoicing Address Flag
        $address->isDeliveryAddress = true;
        //====================================================================//
        // Update Address
        $this->createOrUpdateAddress($parent, $address);
    }

    /**
     * Create or Update Address
     */
    private function createOrUpdateAddress(Company|Contact $parent, Address $address): void
    {
        if (empty($address->id)) {
            //====================================================================//
            // Create Address
            $this->connexion->post(
                $this->getBaseUri($parent),
                $this->hydrator->extract($address)
            );
        } else {
            //====================================================================//
            // Update Address
            $this->connexion->put(
                sprintf("%s/%d", $this->getBaseUri($parent), $address->id),
                $this->hydrator->extract($address)
            );
        }
    }

    /**
     * Build Addresses Base Uri
     */
    private function getBaseUri(Company|Contact $parent): string
    {
        return match ($parent::class) {
            Company::class => sprintf("/companies/%d/addresses", $parent->id),
            Contact::class => sprintf("/contacts/%d/addresses", $parent->id),
            default => "/addresses"
        };
    }

    /**
     * Check if Company Addresses are Similar
     */
    private function isBothInvoicingAndDelivery(Company|Contact $parent): bool
    {
        $invoicingId = $parent->invoicingAddress?->id ?? null;
        $deliveryId = $parent->deliveryAddress?->id ?? null;

        return $invoicingId && $deliveryId && ($invoicingId == $deliveryId);
    }
}
