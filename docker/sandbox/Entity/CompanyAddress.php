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

namespace App\Entity;

use ApiPlatform\Metadata as API;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Address model for Companies.
 */
#[ORM\Entity]
#[API\ApiResource(
    uriTemplate: '/companies/{companyId}/addresses',
    operations: array(
        new API\GetCollection(),
    ),
    uriVariables: array(
        'companyId' => new API\Link(toProperty: 'company', fromClass: Company::class),
    )
)]
#[API\ApiResource(
    uriTemplate: '/companies/{companyId}/addresses/{id}',
    operations: array(
        new API\Get(),
        new API\Put(),
        new API\Delete(),
    ),
    uriVariables: array(
        'companyId' => new API\Link(toProperty: 'company', fromClass: Company::class),
        'id' => new API\Link(fromClass: CompanyAddress::class),
    ),
    normalizationContext: array("groups" => array("read"))
)]
class CompanyAddress extends AbstractSellsyObject
{
    use Address\MainTrait;

    /**
     * Unique Identifier.
     */
    #[
        Assert\Type("integer"),
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: Types::INTEGER),
        Serializer\Groups("read")
    ]
    public int $id;

    /**
     * Link to Parent Company
     */
    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'addresses')]
    protected ?Company $company;

    //====================================================================//
    // Getters & Setters
    //====================================================================//

    /**
     * Get Address Parent Company
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * Set Address Parent Company
     */
    public function setCompany(Company $company): static
    {
        $this->company = $company;

        return $this;
    }
}
