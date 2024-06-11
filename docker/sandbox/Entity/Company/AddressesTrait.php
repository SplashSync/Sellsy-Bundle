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

namespace App\Entity\Company;

use ApiPlatform\Metadata as API;
use App\Entity\CompanyAddress;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Company links with Invoicing & Delivery Addresses
 */
trait AddressesTrait
{
    /**
     * Storage for Company Addresses
     *
     * @var Collection<CompanyAddress>
     */
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: CompanyAddress::class)]
    #[API\Link(toProperty: 'company')]
    protected Collection $addresses;

    /**
     * Get Company Delivery Address
     */
    public function addAddress(CompanyAddress $address): static
    {
        $address->company = $this;
        $this->addresses->add($address);

        return $this;
    }

    /**
     * Get Company Delivery Address
     */
    protected function getInvoicingAddress(): ?CompanyAddress
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
    protected function getDeliveryAddress(): ?CompanyAddress
    {
        foreach ($this->addresses as $address) {
            if ($address->isDeliveryAddress) {
                return $address;
            }
        }

        return null;
    }
}
