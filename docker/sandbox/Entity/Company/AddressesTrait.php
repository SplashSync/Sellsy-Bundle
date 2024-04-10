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

use App\Entity\Addresses;
use Doctrine\ORM\Mapping as ORM;

/**
 * Company / Contacts links with Invoicing & Delivery Addresses
 */
trait AddressesTrait
{
    #[ORM\OneToOne(targetEntity: Addresses::class, cascade: array("all"))]
    private ?Addresses $invoicing_address = null;

    #[ORM\OneToOne(targetEntity: Addresses::class, cascade: array("all"))]
    private ?Addresses $delivery_address = null;
}
