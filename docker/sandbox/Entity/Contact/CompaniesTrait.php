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

namespace App\Entity\Contact;

use App\Entity\Company;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Manage Links between Companies & Contacts
 */
trait CompaniesTrait
{
    /**
     * Storage for Contact Companies
     *
     * @var Collection<Company>
     */
    #[ORM\ManyToMany(targetEntity: Company::class, mappedBy: "contacts")]
    protected Collection $companies;

    /**
     * Get Contact Companies
     *
     * @return Collection<Company>
     */
    public function getCompanies(): Collection
    {
        return $this->companies ??= new ArrayCollection();
    }
}
