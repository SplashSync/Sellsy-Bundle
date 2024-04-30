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

namespace Splash\Connectors\Sellsy\Objects;

use Exception;
use Splash\Connectors\Sellsy\Connector\SellsyConnector;
use Splash\Connectors\Sellsy\Models\Actions\SellsyListAction;
use Splash\Connectors\Sellsy\Models\Metadata as ApiModels;
use Splash\OpenApi\Action\Json;
use Splash\OpenApi\Models\Metadata\AbstractApiMetadataObject;

/**
 * OpenApi Implementation for Sellsy Company Object
 */
class ThirdParty extends AbstractApiMetadataObject
{
    //====================================================================//
    // General Class Variables
    //====================================================================//

    /**
     * @var ApiModels\Company
     */
    protected object $object;

    /**
     * Class Constructor
     *
     * @param SellsyConnector $connector
     *
     * @throws Exception
     */
    public function __construct(
        protected SellsyConnector $connector
    ) {
        parent::__construct(
            $connector->getMetadataAdapter(),
            $connector->getConnexion(),
            $connector->getHydrator(),
            ApiModels\Company::class
        );
        $this->visitor->setTimezone("UTC");
        //====================================================================//
        // Prepare Api Visitor
        $this->visitor->setModel(
            ApiModels\Company::class,
            "/companies",
            "/companies/{id}".ApiModels\Company\CompanyEmbed::getUriQuery(),
            array("id")
        );
        $this->visitor->setUpdateAction(Json\PutAction::class);
        $this->visitor->setListAction(
            SellsyListAction::class,
            array(
                "filterKey" => "search[user_ref__contains][]",
                "pageKey" => null,
                "offsetKey" => "offset"
            )
        );
    }

    //====================================================================//
    // DEBUG
    //====================================================================//

    /**
     * Update Request Object
     *
     * @param bool $needed Is This Update Needed
     *
     * @return null|string Object ID of False if Failed to Update
     */
    public function update(bool $needed): ?string
    {
        //====================================================================//
        // Execute Generic Save
        //        dd($this->visitor->getHydrator()->extract($this->object));

        $objectId = parent::update($needed);
        //====================================================================//
        // Update Invoicing Address
        if (!$objectId) {
            return $objectId;
        }
        //====================================================================//
        // Update Invoicing Address
        if ($this->isToUpdate("InvoicingAddress")) {
            $this->connector
                ->getAddressUpdater()
                ->createOrUpdateInvoicingAddress($this->object)
            ;
        }
        //====================================================================//
        // Update Delivery Address
        if ($this->isToUpdate("DeliveryAddress")) {
            $this->connector
                ->getAddressUpdater()
                ->createOrUpdateDeliveryAddress($this->object)
            ;
        }

        return $objectId;
    }
}
