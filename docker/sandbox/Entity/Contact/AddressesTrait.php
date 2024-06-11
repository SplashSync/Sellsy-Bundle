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

namespace App\Entity\Contact;

use ApiPlatform\Metadata as API;
use App\Entity\ContactAddress;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Contacts links with Invoicing & Delivery Addresses
 */
trait AddressesTrait
{
    /**
     * Storage for Contacts Addresses
     *
     * @var Collection<ContactAddress>
     */
    #[ORM\OneToMany(mappedBy: 'contact', targetEntity: ContactAddress::class)]
    #[API\Link(toProperty: 'contact')]
    protected Collection $addresses;

    /**
     * Get Company Delivery Address
     */
    public function addAddress(ContactAddress $address): static
    {
        $address->contact = $this;
        $this->addresses->add($address);

        return $this;
    }

    /**
     * Get Company Delivery Address
     */
    protected function getInvoicingAddress(): ?ContactAddress
    {
        foreach ($this->addresses as $address) {
            if ($address->isInvoicingAddress) {
                return $address;
            }
        }

        return null;
    }

    /**
     * Get Company Delivery Address
     */
    protected function getDeliveryAddress(): ?ContactAddress
    {
        foreach ($this->addresses as $address) {
            if ($address->isDeliveryAddress) {
                return $address;
            }
        }

        return null;
    }
}
