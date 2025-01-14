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

namespace Splash\Connectors\Sellsy\Services;

use Splash\Connectors\Sellsy\Dictionary\WebhookConfig;
use Splash\Connectors\Sellsy\Models\Connector\SellsyConnectorAwareTrait;
use Splash\Connectors\Sellsy\Models\Webhooks\AbstractWebhookConfig;
use Splash\Connectors\Sellsy\Objects\Webhook;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class WebhooksManager
{
    use SellsyConnectorAwareTrait;

    /**
     * @param RouterInterface $router
     */
    public function __construct(
        private readonly RouterInterface $router
    ) {
    }

    /**
     * Check & Update Sellsy Api Account WebHooks.
     *
     * @return bool
     */
    public function updateWebHooks() : bool
    {
        //====================================================================//
        // Load List of Currently Installed Webhooks
        $webHooks = $this->getInstalledWebhooks();
        //====================================================================//
        // Walk on Required WebHooks
        foreach (WebhookConfig::all() as $webhookConfig) {
            //====================================================================//
            // Update Splash WebHook Configuration
            if (false === $this->updateWebHookConfig($webHooks, $webhookConfig)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check & Update Api WebHooks Configuration.
     */
    private function updateWebHookConfig(
        array $webHooks,
        AbstractWebhookConfig $webhookConfig
    ) : bool {
        //====================================================================//
        // Generate Final Webhooks Url
        if (!$webhookUrl = $this->getWebHooksUrl($webhookConfig)) {
            return false;
        }
        //====================================================================//
        // Get Default Channel
        $channel = $webhookConfig->getChanel($this->connector);
        //====================================================================//
        // Filter & Clean List Of WebHooks
        foreach ($webHooks as $webHook) {
            //====================================================================//
            // This is Pointed WebHook is Valid
            if ($channel == $webHook["default_channel"]) {
                //====================================================================//
                // Update WebHook
                return (false !== $this->connector->setObject(
                    "Webhook",
                    $webHook["id"],
                    $webhookConfig->getConfiguration($this->connector, $webhookUrl)
                ));
            }
        }

        //====================================================================//
        // Add Splash WebHooks
        return (false !== $this->connector->setObject(
            "Webhook",
            null,
            $webhookConfig->getConfiguration($this->connector, $webhookUrl)
        ));
    }

    /**
     * Get List of Installed Webhooks
     *
     * @return array<string, string[]>
     */
    private function getInstalledWebhooks(): array
    {
        //====================================================================//
        // Create Object Class
        $webHook = new Webhook($this->connector);
        $webHook->configure("Webhook", $this->connector->getWebserviceId(), $this->connector->getConfiguration());
        //====================================================================//
        // Get List Of WebHooks for this List
        $webHooks = $webHook->objectsList();
        if (isset($webHooks["meta"])) {
            unset($webHooks["meta"]);
        }

        return $webHooks;
    }

    /**
     * Get Connector Url for Webhooks
     */
    private function getWebHooksUrl(AbstractWebhookConfig $webhookConfig) : ?string
    {
        //====================================================================//
        // Connector SelfTest
        if (!$this->connector->selfTest()) {
            return null;
        }
        //====================================================================//
        // Setup Hostname for WebHooks
        $this->router->getContext()
            ->setHost($this->getHostname())
            ->setScheme("https")
        ;

        //====================================================================//
        // Generate WebHook Url
        return $this->router->generate(
            'splash_connector_action',
            array(
                'connectorName' => $this->connector->getProfile()["name"],
                'webserviceId' => $this->connector->getWebserviceId(),
                't' => $webhookConfig->getObjectType()
            ),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * Get HostName for Shopify Webhooks
     *
     * @return string
     */
    private function getHostname(): string
    {
        static $hostAliases = array(
            "localhost" => "eu-99.splashsync.com",
            "toolkit.shopify.local" => "eu-99.splashsync.com",
            "eu-99.splashsync.com" => "app-99.splashsync.com",
            "www.splashsync.com" => "proxy.splashsync.com",
            "app.splashsync.com" => "proxy.splashsync.com",
            "admin.splashsync.com" => "proxy.splashsync.com"
        );
        //====================================================================//
        // Get Current Server Name
        $hostName = $this->router->getContext()->getHost();
        //====================================================================//
        // Detect Server Aliases
        foreach ($hostAliases as $source => $target) {
            if (str_contains($source, $hostName)) {
                $hostName = $target;
            }
        }

        return $hostName;
    }
}
