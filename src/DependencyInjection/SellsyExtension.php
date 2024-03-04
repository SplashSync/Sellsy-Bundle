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

namespace Splash\Connectors\Sellsy\DependencyInjection;

use KnpU\OAuth2ClientBundle\Client\OAuth2PKCEClient;
use Splash\Connectors\Sellsy\Oauth2\PrivateClient;
use Splash\Connectors\Sellsy\Oauth2\SandboxClient;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Loads and manages bundle configuration
 */
class SellsyExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        //====================================================================//
        // CONFIGURE Splash OAuth2 Shopify Client
        $container->prependExtensionConfig(
            "knpu_oauth2_client",
            array(
                "clients" => array(
                    SandboxClient::CODE => array(
                        "type" => "generic",
                        "provider_class" => SandboxClient::class,
                        "client_id" => '',
                        "client_secret" => '',
                        "redirect_route" => "splash_connector_action_master",
                        "redirect_params" => array(
                            "connectorName" => "sellsy",
                        ),
                    ),
                    PrivateClient::CODE => array(
                        "type" => "generic",
                        "provider_class" => PrivateClient::class,
                        "client_class" => OAuth2PKCEClient::class,
                        "client_id" => '',
                        "client_secret" => '',
                        "redirect_route" => "splash_connector_action_master",
                        "redirect_params" => array(
                            "connectorName" => "sellsy",
                        ),
                    ),
                ),
            )
        );
    }
}
