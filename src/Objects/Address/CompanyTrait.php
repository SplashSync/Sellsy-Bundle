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
trait CompanyTrait
{
    /**
     * Build Fields using FieldFactory
     */
    protected function buildCompanyFields(): void
    {
        //====================================================================//
        // Contact First Company ID
        $this->fieldsFactory()->create((string) self::objects()->encode("ThirdParty", SPL_T_ID))
            ->identifier("company")
            ->name("Company")
            ->description("First Company where Contact is Attached")
            ->isNotTested()
        ;
        //====================================================================//
        // Contact Single Company ID
        $this->fieldsFactory()->create((string) self::objects()->encode("ThirdParty", SPL_T_ID))
            ->identifier("company_single")
            ->name("Single Company")
            ->description("Single Company where Contact is Attached")
            ->microData("http://schema.org/Organization", "ID")
        ;
    }

    /**
     * Read requested Field
     */
    protected function getCompanyFields(string $key, string $fieldName): void
    {
        //====================================================================//
        // READ FIELD
        switch ($fieldName) {
            case "company":
                $manager = $this->connector->getContactCompaniesManager();
                $this->out[$fieldName] = $manager->getFirst($this->object)?->getObjectId();

                break;
            case "company_single":
                $manager = $this->connector->getContactCompaniesManager();
                $this->out[$fieldName] = null;
                if (1 == count($manager->getAll($this->object))) {
                    $this->out[$fieldName] = $manager->getFirst($this->object)?->getObjectId();
                }

                break;
            default:
                return;
        }

        unset($this->in[$key]);
    }

    /**
     * Write Given Fields
     */
    protected function setCompanyFields(string $fieldName, ?string $fieldData): void
    {
        //====================================================================//
        // READ FIELD
        switch ($fieldName) {
            case "company":
                $manager = $this->connector->getContactCompaniesManager();
                //====================================================================//
                // Verify if Contact is Already Attached
                if (!empty($fieldData) && !$manager->hasCompany($this->object, $fieldData)) {
                    $manager->attachToCompany($this->object, (int) self::objects()->id($fieldData));
                }

                // no break
            case "company_single":
                $manager = $this->connector->getContactCompaniesManager();
                //====================================================================//
                // Compare Current Value
                $companyId = $manager->getFirst($this->object)?->getObjectId();
                if ((1 == count($manager->getAll($this->object))) && ($companyId == $fieldData)) {
                    $this->out[$fieldName] = $manager->getFirst($this->object)?->getObjectId();
                }
                if (empty($fieldData)) {
                    break;
                }
                //====================================================================//
                // Detach Contact from All Companies
                if ($manager->detachFromAll($this->object)) {
                    $manager->attachToCompany($this->object, (int) self::objects()->id($fieldData));
                }

                break;
            default:
                return;
        }

        unset($this->in[$fieldName]);
    }
}
