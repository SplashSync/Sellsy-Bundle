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
 * Class representing the Address model for Contacts.
 */
#[ORM\Entity]
#[API\ApiResource(
    uriTemplate: '/contacts/{contactId}/addresses',
    operations: array(
        new API\GetCollection(),
    ),
    uriVariables: array(
        'contactId' => new API\Link(toProperty: 'contact', fromClass: Contact::class),
    )
)]
#[API\ApiResource(
    uriTemplate: '/contacts/{contactId}/addresses/{id}',
    operations: array(
        new API\Get(),
        new API\Put(),
        new API\Delete(),
    ),
    uriVariables: array(
        'contactId' => new API\Link(toProperty: 'contact', fromClass: Contact::class),
        'id' => new API\Link(fromClass: ContactAddress::class),
    ),
    normalizationContext: array("groups" => array("read"))
)]
class ContactAddress extends AbstractSellsyObject
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
     * Link to Parent Contact
     */
    #[ORM\ManyToOne(targetEntity: Contact::class, inversedBy: 'addresses')]
    protected ?Contact $contact;

    //====================================================================//
    // Getters & Setters
    //====================================================================//

    /**
     * Get Address Parent Contact
     */
    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    /**
     * Set Address Parent Contact
     */
    public function setContact(Contact $contact): static
    {
        $this->contact = $contact;

        return $this;
    }
}
