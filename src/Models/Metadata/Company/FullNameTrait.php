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

use Splash\Core\Helpers\FullNameParser;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Splash\Templates\ThirdPartyFields;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait FullNameTrait
{
    /**
     * Company's name.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        Serializer\SerializedName("name"),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE, SplGroups::LIST, SplGroups::REQUIRED)),
        SPL\Template(ThirdPartyFields::NAME),
        SPL\Accessor(getter: "getCompanyName", setter: "setCompanyName"),
        SPL\Flags(listed: true),
        SPL\IsRequired,
    ]
    protected string $name;

    /**
     * Virtual First Name.
     */
    #[
        Serializer\Ignore,
        SPL\Template(ThirdPartyFields::FIRSTNAME),
        SPL\Associations(array("firstName", "lastName"))
    ]
    protected ?string $firstName = null;

    /**
     * Virtual Last Name.
     */
    #[
        Serializer\Ignore,
        SPL\Template(ThirdPartyFields::LASTNAME),
        SPL\Associations(array("firstName", "lastName"))
    ]
    protected ?string $lastName = null;

    #[
        Serializer\Ignore,
    ]
    private FullNameParser $fullNameParser;

    /**
     * Get Name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set Name
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set Company Name
     */
    public function setCompanyName(string $name): static
    {
        $this->getFullNameParser()->setCompanyName($name);
        $this->refreshFullName();

        return $this;
    }

    /**
     * Get Company Name
     */
    public function getCompanyName(): string
    {
        return (string) $this->getFullNameParser()->getCompanyName();
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
        $this->refreshFullName();

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
        $this->refreshFullName();

        return $this;
    }

    /**
     * Keep the serialized Name in sync with the Name Parser
     *
     * JMS used to rebuild it on PreSerialize: with Symfony Serializer, the
     * property is read as is, so it is kept up to date on every change.
     */
    private function refreshFullName(): void
    {
        $this->name = (string) $this->getFullNameParser()->getFullName();
    }

    /**
     * Get Full Name Parser
     */
    private function getFullNameParser(): FullNameParser
    {
        return $this->fullNameParser ??= new FullNameParser($this->name ?? null);
    }
}
