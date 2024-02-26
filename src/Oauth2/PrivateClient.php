<?php

namespace Splash\Connectors\Sellsy\Oauth2;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use Splash\Security\Oauth2\Model\ConfigurableProvider;

/**
 * Oauth2 Client For Sellsy Private API
 */
class PrivateClient extends ConfigurableProvider
{
    use BearerAuthorizationTrait;

    const CODE = "sellsy_private";

    const ENDPOINT = "https://api.sellsy.com/v2";

    /**
     * @inheritDoc
     */
    public function getBaseAuthorizationUrl(): string
    {
        return "https://login.sellsy.com/oauth2/authorization";
    }

    /**
     * @inheritDoc
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return "https://login.sellsy.com/oauth2/access-tokens";
    }

    /**
     * @inheritDoc
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return self::ENDPOINT."/me";
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultScopes(): array
    {
        return array();
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
    }
}