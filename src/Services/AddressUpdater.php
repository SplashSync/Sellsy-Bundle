<?php

namespace Splash\Connectors\Sellsy\Services;

use RuntimeException;
use Splash\Client\Splash;
use Splash\OpenApi\Hydrator\Hydrator;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;
use Splash\Connectors\Sellsy\Models\Metadata\Addresses\Addresses;

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
     * @param Addresses|null $address
     * @param string $addressType
     * @return void
     */
    public function createOrUpdateAddress(?Addresses $address, string $addressType): void
    {
        if (!$address) {
            $address = new Addresses();
        }
        switch ($addressType) {
            case 'invoicing':
                if ($address->name === '') {
                    $address->name = 'Addresse de facturation';
                }
                $address->isInvoicingAddress = true;
                break;

            case 'delivery':
                if ($address->name === '') {
                    $address->name = 'Addresse de livraison';
                }
                $address->isDeliveryAddress = true;
                break;

            default:
                throw new RuntimeException('Invalid address type');
        }
    }
}