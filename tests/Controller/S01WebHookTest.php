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

use Splash\Bundle\Phpunit\Assertions\ConnectorValidator;
use Splash\Bundle\Phpunit\ConnectorTestCase;
use Splash\Connectors\Sellsy\Connector\SellsyConnector;
use Splash\Connectors\Sellsy\Dictionary\WebhookArgs;
use Splash\Core\Dictionary\SplOperations;
use Splash\Validator\Assertions\Objects\CommitValidator;

/**
 * Test of Sellsy Connector WebHook Controller
 */
class S01WebHookTest extends ConnectorTestCase
{
    const CONNECTOR = "ThisIsSandBoxWsId";

    const PING_RESPONSE = '{"success":true}';

    /**
     * User Agent Sellsy sends with its notifications
     */
    const USER_AGENT = "Sellsy Tester";

    /**
     * Test WebHook For Ping
     */
    public function testWebhookPing(): void
    {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector(self::CONNECTOR);
        $this->assertInstanceOf(SellsyConnector::class, $connector);

        //====================================================================//
        // Ping Action -> GET -> OK
        ConnectorValidator::assertPublicActionWorks($connector, null, array(), "GET");
        $this->assertEquals(self::PING_RESPONSE, ConnectorValidator::getResponseContents());

        //====================================================================//
        // Any other Method without Notification -> KO
        foreach (array("POST", "PUT", "DELETE") as $method) {
            ConnectorValidator::assertPublicActionFail($connector, null, array(), $method);
        }
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
        $connector = $this->getConnector(self::CONNECTOR);
        $this->assertInstanceOf(SellsyConnector::class, $connector);
        //====================================================================//
        // Setup Client
        $this->configure();
        //====================================================================//
        // POST MODE
        ConnectorValidator::assertPublicActionWorks(
            $connector,
            null,
            array(WebhookArgs::INDEX => json_encode($data)),
            "POST"
        );
        $this->assertEquals(self::PING_RESPONSE, ConnectorValidator::getResponseContents());
        CommitValidator::assertIsLastCommitted($action, $objectType, $objectId);
    }

    /**
     * Test WebHook Requests Sellsy did not send
     *
     * @return void
     */
    public function testWebhookRejectsUnknownSenders(): void
    {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector(self::CONNECTOR);
        $this->assertInstanceOf(SellsyConnector::class, $connector);
        //====================================================================//
        // Setup Client with a Foreign User Agent
        self::getTestClient()->setServerParameter("HTTP_user-agent", "Not Sellsy");
        //====================================================================//
        // Valid Notification, Wrong Sender => KO
        ConnectorValidator::assertPublicActionFail(
            $connector,
            null,
            array(WebhookArgs::INDEX => json_encode(array(
                WebhookArgs::ACTION => WebhookArgs::UPDATED,
                WebhookArgs::OBJECT_ID => uniqid(),
                WebhookArgs::OBJECT_TYPE => "client",
            ))),
            "POST"
        );
        //====================================================================//
        // Sellsy Sender, but no Notification => KO
        $this->configure();
        ConnectorValidator::assertPublicActionFail($connector, null, array(), "POST");
        //====================================================================//
        // Sellsy Sender, but Malformed Notification => KO
        ConnectorValidator::assertPublicActionFail(
            $connector,
            null,
            array(WebhookArgs::INDEX => "This is not a json notification"),
            "POST"
        );
    }

    /**
     * Test Setup & Verification of Account WebHooks
     */
    public function testWebhooksSetup(): void
    {
        //====================================================================//
        // Load Connector
        $connector = $this->getConnector(self::CONNECTOR);
        $this->assertInstanceOf(SellsyConnector::class, $connector);
        $webhooksManager = $connector->getLocator()->getWebhooksManager();
        //====================================================================//
        // Install Required WebHooks on the Account
        $this->assertTrue($webhooksManager->updateWebHooks());
        $this->assertTrue($webhooksManager->verifyWebHooks());
        //====================================================================//
        // Setup is Idempotent: no duplicated WebHook on a second run
        $installed = count($webhooksManager->getInstalledWebhooks());
        $this->assertTrue($webhooksManager->updateWebHooks());
        $this->assertCount($installed, $webhooksManager->getInstalledWebhooks());
    }

    /**
     * Generate Fake Inputs for WebHook Requests
     *
     * One notification per Sellsy object type & event, so that every mapping
     * of the Webhook Configs is walked through.
     *
     * @return array
     */
    public function webHooksInputsProvider(): array
    {
        $hooks = array();
        //====================================================================//
        // Sellsy Events, with the Splash Action they trigger
        $events = array(
            "CREATED" => array(WebhookArgs::CREATED, SplOperations::CREATE),
            "UPDATED" => array(WebhookArgs::UPDATED, SplOperations::UPDATE),
            "ANYTHING" => array(WebhookArgs::ANYTHING, SplOperations::UPDATE),
            "DELETED" => array(WebhookArgs::DELETED, SplOperations::DELETE),
        );
        //====================================================================//
        // Sellsy Related Types, with the Splash Object they feed
        $objects = array(
            "CUST" => array("client", "ThirdParty"),
            "ADD" => array("people", "Address"),
            "PRD" => array("item", "Product"),
        );
        //====================================================================//
        // Walk on Objects & Events
        foreach ($objects as $prefix => $object) {
            list($relatedType, $objectType) = $object;
            foreach ($events as $code => $event) {
                list($eventName, $action) = $event;
                $objectId = uniqid();
                $hooks[sprintf("%s-%s", $prefix, $code)] = array(
                    array(
                        WebhookArgs::ACTION => $eventName,
                        WebhookArgs::OBJECT_ID => $objectId,
                        WebhookArgs::OBJECT_TYPE => $relatedType,
                    ),
                    $objectType,
                    $action,
                    $objectId,
                );
            }
        }

        return $hooks;
    }

    /**
     * Configure Client Headers for Sellsy Requests
     */
    private function configure(): void
    {
        self::getTestClient()->setServerParameter("HTTP_user-agent", self::USER_AGENT);
    }
}
