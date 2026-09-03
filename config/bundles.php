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

return array(
    //==============================================================================
    // SPLASH BUNDLES
    Splash\Connectors\Sellsy\SellsyBundle::class => array("all" => true),
    //==============================================================================
    // Oauth2 Bundle & its Client Bundle
    //
    // The Toolkit requires both packages, but does not register their Bundles:
    // activation is left to the project. Should the Toolkit register them one
    // day, these two lines stay harmless: the Kernel merges bundles by class name.
    KnpU\OAuth2ClientBundle\KnpUOAuth2ClientBundle::class => array("all" => true),
    Splash\Security\Oauth2\SplashOauth2Bundle::class => array("all" => true),
);
