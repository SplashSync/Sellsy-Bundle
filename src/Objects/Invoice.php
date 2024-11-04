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
use Splash\Connectors\Sellsy\Models\Metadata\Payment;
use Splash\Connectors\Sellsy\Objects\Common\RowsParserTrait;
use Splash\Models\Objects\IntelParserTrait;
use Splash\OpenApi\Action\Json;
use Splash\OpenApi\Models\Metadata\AbstractApiMetadataObject;
use Webmozart\Assert\Assert;

/**
 * OpenApi Implementation for Sellsy Invoice Object
 */
class Invoice extends AbstractApiMetadataObject
{
    use IntelParserTrait;
    use RowsParserTrait;

    //====================================================================//
    // General Class Variables
    //====================================================================//

    /**
     * @var ApiModels\Invoice
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
            ApiModels\Invoice::class
        );
        $this->visitor->setTimezone("UTC");
        //====================================================================//
        // Prepare Api Visitor
        $this->visitor->setModel(
            ApiModels\Invoice::class,
            "/invoices",
            "/invoices/{id}", //.ApiModels\Invoice\InvoiceEmbed::getUriQuery(),
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
        //                        dd($this->visitor->getHydrator()->extract($this->object));
        //                dump($this->visitor->getHydrator()->extract($this->object));
        //

        return parent::update($needed);
        //        //====================================================================//
        //        // Update Invoicing Address
        //        if (!$objectId) {
        //            return $objectId;
        //        }
        //        //====================================================================//
        //        // Update Invoicing Address
        //        if ($this->isToUpdate("InvoicingAddress")) {
        //            $this->connector
        //                ->getAddressUpdater()
        //                ->createOrUpdateInvoicingAddress($this->object)
        //            ;
        //        }
        //        //====================================================================//
        //        // Update Delivery Address
        //        if ($this->isToUpdate("DeliveryAddress")) {
        //            $this->connector
        //                ->getAddressUpdater()
        //                ->createOrUpdateDeliveryAddress($this->object)
        //            ;
        //        }
        //
    }

    /**
     * @inheritdoc
     */
    public function load(string $objectId): ?object
    {
        //====================================================================//
        // Load Remote Object
        $object = parent::load($objectId);
        if ($object instanceof ApiModels\Invoice) {
            //====================================================================//
            // Load Invoice Linked Payments
            $object->payments = $this->fetchPayments($objectId);
        }

        return $object;
    }

    /**
     * Load Invoice Linked Payments
     *
     * @return ApiModels\Payment[]
     */
    public function fetchPayments(string $objectId): array
    {
        //====================================================================//
        // Fetch RAW List of Invoice Payements
        $rawList = $this->visitor->getConnexion()->get("/invoices/".$objectId."/payments?limit=100");
        if (!$rawList) {
            return array();
        }
        $payments = $this->visitor->getHydrator()->hydrateMany($rawList['data'] ?? array(), Payment::class);
        Assert::allIsInstanceOf($payments, Payment::class);

        return $payments;
    }
}
