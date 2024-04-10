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

use RuntimeException;
use Splash\Connectors\Sellsy\Models\Metadata\Addresses\Addresses;
use Splash\OpenApi\Hydrator\Hydrator;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;

/**
 *
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
     * Create or Update Address
     *
     * @param null|Addresses $address
     * @param string         $addressType
     *
     * @return void
     */
    public function createOrUpdateAddress(?Addresses $address, string $addressType): void
    {
        if (!$address) {
            $address = new Addresses();
        }
        switch ($addressType) {
            case 'invoicing':
                if ('' === $address->name) {
                    $address->name = 'Addresse de facturation';
                }
                $address->isInvoicingAddress = true;

                break;
            case 'delivery':
                if ('' === $address->name) {
                    $address->name = 'Addresse de livraison';
                }
                $address->isDeliveryAddress = true;

                break;
            default:
                throw new RuntimeException('Invalid address type');
        }
    }
}
