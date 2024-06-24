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
use Splash\Models\Objects\IntelParserTrait;
use Splash\OpenApi\Action\Json;
use Splash\OpenApi\Models\Metadata\AbstractApiMetadataObject;

/**
 * OpenApi Implementation for Sellsy Product Object
 */
class Product extends AbstractApiMetadataObject
{
    use IntelParserTrait;
    use Product\PriceTrait;

    //====================================================================//
    // General Class Variables
    //====================================================================//

    /**
     * @var ApiModels\Item
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
            ApiModels\Item::class
        );
        $this->visitor->setTimezone("UTC");
        //====================================================================//
        // Prepare Api Visitor
        $this->visitor->setModel(
            ApiModels\Item::class,
            "/items",
            "/items/{id}",
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
     * {@inheritdoc}
     */
    //        public function objectsList(?string $filter = null, array $params = array()): array
    //        {
    //            $this->visitor->getConnexion()->get("/items") ?? array();
    //    //        dd($this);
    //
    //            dd($this->visitor->getLastResponse());
    //            return $this->visitor->list($filter, $params)->getArrayResults() ?? array();
    //        }
    //    /**
    //     * Update Request Object
    //     *
    //     * @param bool $needed Is This Update Needed
    //     *
    //     * @return null|string Object ID of False if Failed to Update
    //     */
    //    public function update(bool $needed): ?string
    //    {
    //        dd($this->visitor->getHydrator()->extract($this->object));
    //        return parent::update($needed);
    //    }
}
