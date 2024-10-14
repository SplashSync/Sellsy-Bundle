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

namespace Splash\Connectors\Sellsy\Objects\Address;

/**
 * Access to Contact Companies Links
 */
trait CompanyLinksTrait
{
    /**
     * Build Fields using FieldFactory
     */
    protected function buildCompanyLinksFields(): void
    {
        //====================================================================//
        // Contact Companies ID
        $this->fieldsFactory()->create((string) self::objects()->encode("ThirdParty", SPL_T_ID))
            ->identifier("id")
            ->inList("compagnies")
            ->name("Company ID")
            ->isReadOnly()
        ;
        //====================================================================//
        // Contact Companies Main Flag
        $this->fieldsFactory()->create(SPL_T_BOOL)
            ->identifier("main")
            ->inList("compagnies")
            ->name("Is Main")
            ->isReadOnly()
        ;
        //====================================================================//
        // Contact Companies Invoicing Flag
        $this->fieldsFactory()->create(SPL_T_BOOL)
            ->identifier("invoicing")
            ->inList("compagnies")
            ->name("Is Invoicing")
            ->isReadOnly()
        ;
        //====================================================================//
        // Contact Companies Dunning Flag
        $this->fieldsFactory()->create(SPL_T_BOOL)
            ->identifier("dunning")
            ->inList("compagnies")
            ->name("Is Dunning")
            ->isReadOnly()
        ;
    }

    /**
     * Read requested Field
     */
    protected function getCompanyLinksFields(string $key, string $fieldName): void
    {
        //====================================================================//
        // Check if List field & Init List Array
        $fieldId = self::lists()->initOutput($this->out, "compagnies", $fieldName);
        if (!$fieldId) {
            return;
        }
        //====================================================================//
        // Fill List with Data
        foreach ($this->connector->getLocator()->getContactCompaniesManager()->getAll($this->object) as $index => $companyLink) {
            //====================================================================//
            // READ Fields
            $value = match ($fieldId) {
                "id" => $companyLink->getObjectId(),
                "main" => $companyLink->isMain(),
                "invoicing" => $companyLink->isInvoicing(),
                "dunning" => $companyLink->isDunning(),
                default => null,
            };
            //====================================================================//
            // Insert Data in List
            self::lists()->insert($this->out, "compagnies", $fieldId, $index, $value);
        }
        unset($this->in[$key]);
    }
}
