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
use Splash\Core\Models\Objects\IntelParserTrait;
use Splash\OpenApi\Models\Objects\AbstractRestAndMetadataObject;

/**
 * OpenApi Implementation for Sellsy Product Object
 */
class Product extends AbstractRestAndMetadataObject
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
            $connector->getVisitor(ApiModels\Item::class),
            $connector->getMetadataAdapter(),
            ApiModels\Item::class
        );
        $this->visitor->setListAction(
            SellsyListAction::class,
            array(
                "filterKey" => "reference",
                "pageKey" => null,
                "offsetKey" => "offset"
            )
        );
    }
}
