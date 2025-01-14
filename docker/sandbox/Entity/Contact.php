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
use App\Controller\AddContactAddress;
use App\Controller\CompanyContacts;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Company model.
 */
#[
    ORM\Entity,
    ORM\HasLifecycleCallbacks,
    API\ApiResource(
        operations: array(
            new API\GetCollection(),
            new API\Post(),
            new API\Get(),
            new API\Put(),
            new API\Delete(),
        ),
        normalizationContext: array('groups' => array('read'))
    )
]
#[API\ApiResource(
    uriTemplate: '/contacts/{id}/addresses',
    operations: array(
        new API\Post(
            controller: AddContactAddress::class
        )
    ),
)]
#[API\ApiResource(
    uriTemplate: '/contacts/{id}/companies',
    operations: array(
        new API\GetCollection(
            controller: CompanyContacts::class
        ),
    ),
)]
class Contact extends AbstractSellsyObject
{
    use Contact\MainTrait;
    use Contact\ContactInfosTrait;
    use Contact\ExtrasInfosTrait;
    use Contact\MetadataTrait;
    use Contact\AddressesTrait;
    use Contact\CompaniesTrait;

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

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }

    /**
     * Get Contact Embedded Data
     *
     * @return array<string, null|ContactAddress>
     */
    #[
        Serializer\Groups("read"),
        Serializer\SerializedName("_embed"),
        Serializer\MaxDepth(1),
    ]
    public function getEmbed(): array
    {
        return array(
            "invoicing_address" => $this->getInvoicingAddress(),
            "delivery_address" => $this->getDeliveryAddress(),
        );
    }

    //====================================================================//
    // Lifecycle Events
    //====================================================================//

    #[ORM\PrePersist()]
    public function onPrePersist(): void
    {
        $this->created = $this->updated = new DateTime();
    }

    #[ORM\PreUpdate()]
    public function onPreUpdate(): void
    {
        $this->updated = new DateTime();
    }
}
