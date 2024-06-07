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

namespace Splash\Connectors\Sellsy\Oauth2;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use Splash\Security\Oauth2\Model\ConfigurableProvider;

/**
 * Oauth2 Client For Sellsy Sandbox
 */
class SandboxClient extends ConfigurableProvider
{
    use BearerAuthorizationTrait;

    const CODE = "sellsy_sandbox";

    const ENDPOINT = "http://sandbox.sellsy.local";

    /**
     * @inheritDoc
     */
    public function getBaseAuthorizationUrl(): string
    {
        return "http://sandbox.sellsy.local/authorize";
    }

    /**
     * @inheritDoc
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return "http://sandbox.sellsy.local/token";
    }

    /**
     * @inheritDoc
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return "http://sandbox.sellsy.local/me";
    }

    /**
     * @inheritDoc
     */
    protected function getPkceMethod()
    {
        //        return AbstractProvider::PKCE_METHOD_PLAIN;
        return AbstractProvider::PKCE_METHOD_S256;
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultScopes()
    {
        return array("USER");
    }

    /**
     * @inheritDoc
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        // Nothing to Do Here
    }

    /**
     * @inheritDoc
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        // Nothing to Do Here
        /** @phpstan-ignore-line  */
    }
}
