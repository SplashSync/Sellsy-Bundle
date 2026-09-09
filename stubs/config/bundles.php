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

/**
 * Sellsy Bridge Connector Bundles
 *
 * The Sellsy Bundle itself is auto-discovered from connector/Sellsy: only the
 * Bundles it relies on are declared here.
 */
return array(
    //==============================================================================
    // SPLASH OPENAPI BUNDLES
    Splash\Metadata\SplashMetadataBundle::class => array("all" => true),
    Splash\OpenApi\SplashOpenApiBundle::class => array("all" => true),
    //==============================================================================
    // OAUTH2 BUNDLE & ITS CLIENT BUNDLE
    KnpU\OAuth2ClientBundle\KnpUOAuth2ClientBundle::class => array("all" => true),
    Splash\Security\Oauth2\SplashOauth2Bundle::class => array("all" => true),
);
