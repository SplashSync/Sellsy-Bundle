<?php

namespace Splash\Connectors\Sellsy\Objects;

use Exception;
use Splash\Client\Splash;
use Splash\Connectors\Sellsy\Connector\SellsyConnector;
use Splash\Connectors\Sellsy\Models\Actions\SellsyListAction;
use Splash\Connectors\Sellsy\Models\Metadata as ApiModels;
use Splash\Metadata\Services\MetadataAdapter;
use Splash\OpenApi\Models\Metadata\AbstractApiMetadataObject;
use Splash\OpenApi\Visitor\AbstractVisitor as Visitor;
use Splash\OpenApi\Visitor\JsonVisitor;
use Splash\OpenApi\Action\Json;

/**
 * OpenApi Implementation for Sellsy Companies Object
 */
class ThirdParty extends AbstractApiMetadataObject
{
    //====================================================================//
    // General Class Variables
    //====================================================================//

    /**
     * @var ApiModels\Companies
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
    )
    {
        parent::__construct(
            $connector->getMetadataAdapter(),
            $connector->getConnexion(),
            $connector->getHydrator(),
            ApiModels\Companies::class
        );
        $this->visitor->setTimezone("UTC");
        //====================================================================//
        // Prepare Api Visitor
        $this->visitor->setModel(
            ApiModels\Companies::class,
            "/companies",
            "/companies/{id}",
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


//    public function load(string $objectId): ?object
//    {
//        //====================================================================//
//        // Load Remote Object
//        $loadResponse = $this->visitor->load($objectId);
//        if (!$loadResponse->isSuccess()) {
//            return null;
//        }
//        dd(json_decode($this->visitor->getLastResponse()->body));
//
//        return null;
//    }

//    public function objectsList(?string $filter = null, array $params = array()): array
//    {
//        $this->visitor->list($filter, $params)->getArrayResults();
//        dd(json_decode($this->visitor->getLastResponse()->body));
//        dd($this->visitor->list($filter, $params)->getArrayResults());
//
//        return $this->visitor->list($filter, $params)->getArrayResults() ?? array();
//    }

}
