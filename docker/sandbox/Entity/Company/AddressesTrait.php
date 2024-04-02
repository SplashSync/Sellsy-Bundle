<?php

namespace App\Entity\Company;

use App\Entity\Addresses;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


/**
 * Companies / Contacts links with Invoicing & Delivery Addresses
 */
trait AddressesTrait
{
    #[ORM\OneToOne(targetEntity: Addresses::class, cascade: array("all"))]
    private ?Addresses $invoicing_address = null;

    #[ORM\OneToOne(targetEntity: Addresses::class, cascade: array("all"))]
    private ?Addresses $delivery_address = null;
}