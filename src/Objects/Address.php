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
use Splash\Client\Splash;
use Splash\Connectors\Sellsy\Connector\SellsyConnector;
use Splash\Connectors\Sellsy\Models\Actions\Address\AddressListAction;
use Splash\Connectors\Sellsy\Models\Actions\SellsyListAction;
use Splash\Connectors\Sellsy\Models\Metadata as ApiModels;
use Splash\OpenApi\Action\Json;
use Splash\OpenApi\Models\Metadata\AbstractApiMetadataObject;
use Splash\Connectors\Sellsy\Oauth2\PrivateClient;

class Address extends AbstractApiMetadataObject
{
    //====================================================================//
    // General Class Variables
    //====================================================================//

    /**
     * @var ApiModels\Addresses\Addresses
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
            ApiModels\Addresses\Addresses::class
        );
        $this->visitor->setTimezone("UTC");
        //====================================================================//
        // Prepare Api Visitor
        $this->visitor->setModel(
            ApiModels\Addresses\Addresses::class,
            "/companies",
            "/companies/{companyId}/addresses/{id}",
        );
        $this->visitor->setUpdateAction(Json\PutAction::class);
        $this->visitor->setListAction(
            AddressListAction::class,
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
//
//                return null;
//            }
//
//            public function objectsList(?string $filter = null, array $params = array()): array
//            {
//                $this->visitor->list($filter, $params)->getArrayResults();
//                dd(json_decode($this->visitor->getLastResponse()->body));
//                dd($this->visitor->list($filter, $params)->getArrayResults());
//
//                return $this->visitor->list($filter, $params)->getArrayResults() ?? array();
//            }
//
//            /**
//         * Update Request Object
//         *
//         * @param bool $needed Is This Update Needed
//         *
//         * @return null|string Object ID of False if Failed to Update
//         */
//        public function update(bool $needed): ?string
//        {
//            //====================================================================//
//            // Update Remote Object
//            $updateResponse = $this->visitor->update((string) $this->getObjectIdentifier(), $this->object);
//
//            dd(json_decode($this->visitor->getLastResponse()->body));
//            //====================================================================//
//            // Return Object Id or False
//            return $updateResponse->isSuccess()
//                ? $this->getObjectIdentifier()
//                : Splash::log()->errNull(
//                    "Unable to Update Object (".$this->getObjectIdentifier().")."
//                )
//            ;
//        }
}
