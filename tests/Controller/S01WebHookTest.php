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

namespace Splash\Connectors\Sellsy\Test\Controller;

use Splash\Connectors\Sellsy\Connector\SellsyConnector;
use Splash\Connectors\Sellsy\Dictionary\WebhookArgs;
use Splash\Tests\Tools\TestCase;

/**
 * Test of Sellsy Connector WebHook Controller
 */
class S01WebHookTest extends TestCase
{
    const PING_RESPONSE = '{"success":true}';
    const MEMBER = "ThirdParty";
    const FAKE_EMAIL = "fake@exemple.com";
    const METHOD = "JSON";

    /**
     * Test WebHook For Ping
     */
    public function testWebhookPing(): void
    {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector("ThisIsSandBoxWsId");
        $this->assertInstanceOf(SellsyConnector::class, $connector);

        //====================================================================//
        // Ping Action -> GET -> OK
        $this->assertPublicActionWorks($connector, null, array(), "GET");
        $this->assertEquals(self::PING_RESPONSE, $this->getResponseContents());

        //====================================================================//
        // Ping Action -> POST -> KO
        $this->assertPublicActionFail($connector, null, array(), "POST");
        $this->assertPublicActionFail($connector, null, array(), self::METHOD);
        //====================================================================//
        // Ping Action -> PUT -> KO
        $this->assertPublicActionFail($connector, null, array(), "PUT");
        //====================================================================//
        // Ping Action -> DELETE -> KO
        $this->assertPublicActionFail($connector, null, array(), "DELETE");
    }

    /**
     * Test WebHook Member Updates
     *
     * @dataProvider webHooksInputsProvider
     *
     * @param array  $data
     * @param string $objectType
     * @param string $action
     * @param string $objectId
     *
     * @return void
     */
    public function testWebhookRequest(
        array $data,
        string $objectType,
        string $action,
        string $objectId
    ): void {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector("ThisIsSandBoxWsId");
        $this->assertInstanceOf(SellsyConnector::class, $connector);
        //====================================================================//
        // Setup Client
        $this->configure($connector);
        //====================================================================//
        // POST MODE
        $this->assertPublicActionWorks(
            $connector,
            null,
            array(WebhookArgs::INDEX => json_encode($data)),
            "POST"
        );
        $this->assertEquals(self::PING_RESPONSE, $this->getResponseContents());
        $this->assertIsLastCommitted($action, $objectType, $objectId);
    }

    /**
     * Generate Fake Inputs for WebHook Requests
     *
     * @return array
     */
    public function webHooksInputsProvider(): array
    {
        $hooks = array();

        for ($i = 0; $i < 5; $i++) {
            //====================================================================//
            // Add ThirdParty WebHook Test
            $hooks["CUST-CREATED"] = self::getThirdPartyWebHook(SPL_A_CREATE, WebhookArgs::CREATED, uniqid());
            $hooks["CUST-UPDATED"] = self::getThirdPartyWebHook(SPL_A_UPDATE, WebhookArgs::UPDATED, uniqid());
            $hooks["CUST-ANYTHING"] = self::getThirdPartyWebHook(SPL_A_UPDATE, WebhookArgs::ANYTHING, uniqid());
            $hooks["CUST-DELETED"] = self::getThirdPartyWebHook(SPL_A_DELETE, WebhookArgs::DELETED, uniqid());

            //====================================================================//
            // Add Address WebHook Test
            $hooks["ADD-CREATED"] = self::getAddressWebHook(SPL_A_CREATE, WebhookArgs::CREATED, uniqid());
            $hooks["ADD-UPDATED"] = self::getAddressWebHook(SPL_A_UPDATE, WebhookArgs::UPDATED, uniqid());
            $hooks["ADD-ANYTHING"] = self::getAddressWebHook(SPL_A_UPDATE, WebhookArgs::ANYTHING, uniqid());
            $hooks["ADD-DELETED"] = self::getAddressWebHook(SPL_A_DELETE, WebhookArgs::DELETED, uniqid());

            //====================================================================//
            // Add Product WebHook Test
            $hooks["PRD-CREATED"] = self::getProductWebHook(SPL_A_CREATE, WebhookArgs::CREATED, uniqid());
            $hooks["PRD-UPDATED"] = self::getProductWebHook(SPL_A_UPDATE, WebhookArgs::UPDATED, uniqid());
            $hooks["PRD-ANYTHING"] = self::getProductWebHook(SPL_A_UPDATE, WebhookArgs::ANYTHING, uniqid());
            $hooks["PRD-DELETED"] = self::getProductWebHook(SPL_A_DELETE, WebhookArgs::DELETED, uniqid());
            //
            //            //====================================================================//
            //            // Add Order & Invoices WebHook Test
            //            $hooks[] = self::getInvoiceWebHook(SPL_A_CREATE, "orders/create", uniqid());
            //            $hooks[] = self::getInvoiceWebHook(SPL_A_UPDATE, "orders/cancelled", uniqid());
            //            $hooks[] = self::getInvoiceWebHook(SPL_A_UPDATE, "orders/fulfilled", uniqid());
            //            $hooks[] = self::getInvoiceWebHook(SPL_A_UPDATE, "orders/paid", uniqid());
            //            $hooks[] = self::getInvoiceWebHook(SPL_A_UPDATE, "orders/partially_fulfilled", uniqid());
            //            $hooks[] = self::getInvoiceWebHook(SPL_A_UPDATE, "orders/updated", uniqid());
            //            $hooks[] = self::getInvoiceWebHook(SPL_A_DELETE, "orders/delete", uniqid());
        }

        return $hooks;
    }

    /**
     * Configure Client Headers for Sellsy Requests
     */
    private function configure(SellsyConnector $connector): void
    {
        $wsHost = $connector->getParameter("WsHost");
        $this->assertIsString($wsHost);
        $this->getTestClient()->setServerParameter("HTTP_user-agent", "Sellsy Tester");
    }

    /**
     * Generate Fake ThirdParty Inputs for WebHook Request
     *
     * @param string $action
     * @param string $eventName
     * @param string $objectId
     *
     * @return array
     */
    private static function getThirdPartyWebHook(
        string $action,
        string $eventName,
        string $objectId,
    ) : array {
        return array(
            array(
                WebhookArgs::ACTION => $eventName,
                WebhookArgs::OBJECT_ID => $objectId,
                WebhookArgs::OBJECT_TYPE => "client",
            ),
            "ThirdParty",
            $action,
            $objectId
        );
    }

    /**
     * Generate Fake Address Inputs for WebHook Request
     *
     * @param string $action
     * @param string $eventName
     * @param string $objectId
     *
     * @return array
     */
    private static function getAddressWebHook(
        string $action,
        string $eventName,
        string $objectId,
    ) : array {
        return array(
            array(
                WebhookArgs::ACTION => $eventName,
                WebhookArgs::OBJECT_ID => $objectId,
                WebhookArgs::OBJECT_TYPE => "people",
            ),
            "Address",
            $action,
            $objectId
        );
    }

    /**
     * Generate Fake Product Inputs for WebHook Request
     *
     * @param string $action
     * @param string $eventName
     * @param string $objectId
     *
     * @return array
     */
    private static function getProductWebHook(string $action, string $eventName, string $objectId) : array
    {
        return array(
            array(
                WebhookArgs::ACTION => $eventName,
                WebhookArgs::OBJECT_ID => $objectId,
                WebhookArgs::OBJECT_TYPE => "item",
            ),
            "Product",
            $action,
            $objectId
        );
    }

    /**
     * Generate Fake Order & Invoice Inputs for WebHook Request
     *
     * @param string $action
     * @param string $eventName
     * @param string $invoice
     *
     * @return array
     */
    private static function getInvoiceWebHook(string $action, string $eventName, string $invoice) : array
    {
        return array(
            $eventName,
            array(
                "id" => $invoice,
            ),
            "Invoice",
            $action,
            $invoice,
        );
    }
}
