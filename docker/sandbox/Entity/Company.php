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
use App\Controller\AddCompanyAddress;
use App\Controller\AddCompanyContact;
use App\Controller\RemoveCompanyContact;
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
    uriTemplate: '/companies/{id}/addresses',
    operations: array(
        new API\Post(
            controller: AddCompanyAddress::class
        )
    ),
)]
#[API\ApiResource(
    uriTemplate: '/companies/{id}/contacts/{contactId}',
    operations: array(
        new API\Post(
            controller: AddCompanyContact::class,
            read:   false,
            write:  false,
        ),
        new API\Delete(
            controller: RemoveCompanyContact::class,
            read:   false,
            write:  false,
        ),
    ),
)]
class Company extends AbstractSellsyObject
{
    use Company\MainTrait;
    use Contact\ContactInfosTrait;
    use Company\ExtrasInfosTrait;
    use Company\MetadataTrait;
    use Company\AddressesTrait;
    use Company\ContactsTrait;

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
     * Company Type
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Assert\Choice(array("prospect", "client", "supplier")),
        ORM\Column,
        Serializer\Groups("read")
    ]
    public string $type;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }

    /**
     * Get Company Embedded Data
     */
    #[
        Serializer\Groups("read"),
        Serializer\SerializedName("_embed"),
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
        $this->created = $this->updated_at = new DateTime();
    }

    #[ORM\PreUpdate()]
    public function onPreUpdate(): void
    {
        $this->updated_at = new DateTime();
    }
}
