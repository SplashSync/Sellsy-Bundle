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
 * OpenApi Implementation for Sellsy Companies Object
 */
class ThirdParty extends AbstractApiMetadataObject
{
    //====================================================================//
    // General Class Variables
    //====================================================================//

    /**
     * @var ApiModels\Companies\Companies
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
            ApiModels\Companies\Companies::class
        );
        $this->visitor->setTimezone("UTC");
        //====================================================================//
        // Prepare Api Visitor
        $this->visitor->setModel(
            ApiModels\Companies\Companies::class,
            "/companies",
            "/companies/{id}".ApiModels\Companies\CompanyEmbed::getUriQuery(),
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
        $objectId = parent::update($needed);

        //====================================================================//
        // Update Invoicing Address
        if ($objectId && $this->isToUpdate("InvoicingAddress")) {

//            dd($this->object->invoicingAddress);
//            dd($this->visitor->getHydrator()->extract($this->object->invoicingAddress));

            $this->visitor->getConnexion()->put(
                sprintf("/companies/%d/addresses/%d", $this->getObjectIdentifier(),  $this->object->invoicingAddress->id),
                $this->visitor->getHydrator()->extract($this->object->invoicingAddress)
            );
        }

        return $objectId;
    }
//            public function load(string $objectId): ?object
//            {
//                //====================================================================//
//                // Load Remote Object
//                $loadResponse = $this->visitor->load($objectId);
//                if (!$loadResponse->isSuccess()) {
//                    return null;
//                }
//
//                dd(json_decode($this->visitor->getLastResponse()->body));
//                dd($this->visitor->getLastResponse());
//
//                return null;
//            }

    //        public function objectsList(?string $filter = null, array $params = array()): array
    //        {
    //            $this->visitor->list($filter, $params)->getArrayResults();
    //            dd(json_decode($this->visitor->getLastResponse()->body));
    //            dd($this->visitor->list($filter, $params)->getArrayResults());
    //
    //            return $this->visitor->list($filter, $params)->getArrayResults() ?? array();
    //        }

    //        /**
    //     * Update Request Object
    //     *
    //     * @param bool $needed Is This Update Needed
    //     *
    //     * @return null|string Object ID of False if Failed to Update
    //     */
    //    public function update(bool $needed): ?string
    //    {
    //        //====================================================================//
    //        // Update Remote Object
    //        $updateResponse = $this->visitor->update((string) $this->getObjectIdentifier(), $this->object);
    //
    //        dd(json_decode($this->visitor->getLastResponse()->body));
    //        //====================================================================//
    //        // Return Object Id or False
    //        return $updateResponse->isSuccess()
    //            ? $this->getObjectIdentifier()
    //            : Splash::log()->errNull(
    //                "Unable to Update Object (".$this->getObjectIdentifier().")."
    //            )
    //        ;
    //    }
}
