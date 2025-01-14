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

namespace Splash\Connectors\Sellsy\Models\Metadata\Company;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Splash\Models\Helpers\FullNameParser;
use Symfony\Component\Validator\Constraints as Assert;

trait FullNameTrait
{
    /**
     * Company's name.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("name"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        SPL\Field(desc: "Company name"),
        SPL\Microdata("http://schema.org/Organization", "legalName"),
        SPL\Flags(listed: true),
        SPL\IsRequired,
    ]
    protected string $name;

    /**
     * Virtual First Name.
     */
    #[
        JMS\Exclude(),
        SPL\Field(desc: "First Name"),
        SPL\Microdata("http://schema.org/Person", "familyName"),
        SPL\Associations(array("firstName", "lastName"))
    ]
    protected ?string $firstName = null;

    /**
     * Virtual Last Name.
     */
    #[
        JMS\Exclude(),
        SPL\Field(desc: "Last Name"),
        SPL\Microdata("http://schema.org/Person", "givenName"),
        SPL\Associations(array("firstName", "lastName"))
    ]
    protected ?string $lastName = null;

    #[
        JMS\Exclude(),
    ]
    private FullNameParser $fullNameParser;

    /**
     * @return string
     *
     * Get Company Name
     */
    public function getName(): string
    {
        return (string) $this->getFullNameParser()->getCompanyName();
    }

    /**
     * @param string $name
     *
     * @return $this
     *
     * Set Company Name
     */
    public function setName(string $name): static
    {
        $this->getFullNameParser()->setCompanyName($name);

        return $this;
    }

    /**
     * @return null|string
     *
     * Get First Name
     */
    public function getFirstName(): ?string
    {
        return $this->getFullNameParser()->getFirstName();
    }

    /**
     * @param null|string $name
     *
     * @return $this
     *
     * Set First Name
     */
    public function setFirstName(?string $name): static
    {
        $this->getFullNameParser()->setFirstName($name);

        return $this;
    }

    /**
     * @return null|string
     *
     * Get Last Name
     */
    public function getLastName(): ?string
    {
        return $this->getFullNameParser()->getLastName();
    }

    /**
     * @param null|string $name
     *
     * @return $this
     *
     * Set Last Name
     */
    public function setLastName(?string $name): static
    {
        $this->getFullNameParser()->setLastName($name);

        return $this;
    }

    /**
     * Decode User Full Name
     */
    #[JMS\PostDeserialize()]
    public function decodeFullName(): void
    {
        $this->fullNameParser ??= new FullNameParser($this->name ?? null);
    }

    /**
     * Encode User Full Name
     */
    #[JMS\PreSerialize()]
    public function encodeFullName(): static
    {
        $this->name = (string) $this->getFullNameParser()->getFullName();

        return $this;
    }

    /**
     * Get Full Name Parser
     */
    private function getFullNameParser(): FullNameParser
    {
        return $this->fullNameParser ??= new FullNameParser();
    }
}
