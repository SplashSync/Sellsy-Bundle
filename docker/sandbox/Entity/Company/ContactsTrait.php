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

use App\Entity\Contact;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Manage Links between Companies & Contacts
 */
trait ContactsTrait
{
    /**
     * Company Main Contact ID
     */
    #[
        Assert\Type("int"),
        Serializer\Groups("contacts"),
    ]
    public ?int $mainContactId = null;

    /**
     * Company Invoicing Contact ID
     */
    #[
        Assert\Type("int"),
        Serializer\Groups("contacts"),
    ]
    public ?int $invoicingContactId = null;

    /**
     * Company Dunning Contact ID
     */
    #[
        Assert\Type("int"),
        Serializer\Groups("contacts"),
    ]
    public ?int $dunningContactId = null;
    /**
     * Storage for Company Contacts
     *
     * @var Collection<Contact>
     */
    #[ORM\ManyToMany(targetEntity: Contact::class, inversedBy: "companies")]
    protected Collection $contacts;

    /**
     * Add a Contact to Company
     */
    public function addContact(Contact $contact): static
    {
        $this->contacts->add($contact);

        return $this;
    }

    /**
     * Remove a Contact from Company
     */
    public function removeContact(Contact $contact): static
    {
        $this->contacts->removeElement($contact);

        return $this;
    }

    /**
     * Get Company Main Contact ID
     */
    public function getMainContactId(): ?int
    {
        return $this->contacts->first()?->id;
    }

    /**
     * Get Company Invoicing Contact ID
     */
    public function getInvoicingContactId(): ?int
    {
        return $this->getMainContactId();
    }

    /**
     * Get Company Dunning Contact ID
     */
    public function getDunningContactId(): ?int
    {
        return $this->getMainContactId();
    }
}
