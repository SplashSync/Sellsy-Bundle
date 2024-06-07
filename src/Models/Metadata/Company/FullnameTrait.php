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

trait FullnameTrait
{
    /**
     * Company's name.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("name"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        SPL\Field(desc: "Company name"),
        SPL\Microdata("http://schema.org/Organization", "legalName"),
        SPL\Flags(listed: true),
        SPL\IsRequired,
    ]
    protected ?string $name = null;

    /**
     * Virtual First Name.
     */
    #[
        JMS\Exclude(),
        SPL\Field(desc: "First Name"),
        SPL\Microdata("http://schema.org/Person", "familyName"),
    ]
    protected ?string $firstName = null;

    /**
     * Virtual Last Name.
     */
    #[
        JMS\Exclude(),
        SPL\Field(desc: "Last Name"),
        SPL\Microdata("http://schema.org/Person", "givenName"),
    ]
    protected ?string $lastName = null;

    #[
        JMS\Exclude(),
    ]
    private FullNameParser $fullNameParser;

    /**
     * @return null|string Get Company Name
     *
     * Get Company Name
     */
    public function getName(): ?string
    {
        return $this->getFullnameParser()->getCompanyName();
    }

    /**
     * @param null|string $name
     *
     * @return $this
     *
     * Set Company Name
     */
    public function setName(?string $name): static
    {
        $this->getFullnameParser()->setCompanyName($name);

        return $this;
    }

    /**
     * @return null|string
     *
     * Get First Name
     */
    public function getFirstName(): ?string
    {
        return $this->getFullnameParser()->getFirstName();
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
        $this->getFullnameParser()->setFirstName($name);

        return $this;
    }

    /**
     * @return null|string
     *
     * Get Last Name
     */
    public function getLastName(): ?string
    {
        return $this->getFullnameParser()->getLastName();
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
        $this->getFullnameParser()->setLastName($name);

        return $this;
    }

    /**
     * @return void
     *
     * Decode Fullname
     */
    #[JMS\PostDeserialize()]
    public function decodeFullname(): void
    {
        $this->fullNameParser ??= new FullNameParser($this->name);
    }

    /**
     * @return $this
     *
     * Encode Fullname
     */
    #[JMS\PreSerialize()]
    public function encodeFullname(): static
    {
        $this->name = $this->getFullnameParser()->getFullName();

        return $this;
    }

    /**
     * @return FullNameParser
     *
     * Get Fullname Parser
     */
    private function getFullnameParser(): FullNameParser
    {
        return $this->fullNameParser ??= new FullNameParser();
    }
}
