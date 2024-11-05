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

namespace Splash\Connectors\Sellsy\Connexion;

use Httpful\Exception\ConnectionErrorException;
use Httpful\Request;
use Splash\Core\SplashCore as Splash;
use Splash\OpenApi\Models\Connexion\AbstractConnexion;

/**
 * Special Connexion for Sellsy API V1 Requests
 * Only Used in Production, Sandbox uses Normal Connexion
 */
class SellsyApiV1Connexion extends AbstractConnexion
{
    const ENDPOINT = "https://apifeed.sellsy.com/0";

    /**
     * Construct Sellsy V1 API Connexion
     */
    public function __construct(array $headers = null)
    {
        parent::__construct(self::ENDPOINT, $headers);
    }
    /**
     * @inheritDoc
     */
    public function post(string $path, array $data): ?array
    {
        //====================================================================//
        // Restore Connexion Template
        Request::ini($this->getTemplate());

        //====================================================================//
        // Perform Request
        try {
            $response = Request::post(self::ENDPOINT.$path)
                ->sendsForm()
                ->autoParse(true)
                ->body($data)
                ->send();
        } catch (ConnectionErrorException $ex) {
            Splash::log()->err($ex->getMessage());

            return null;
        }

        //====================================================================//
        // Catch Errors in Response
        return self::isErrored($response)
            ? null
            : (array) json_decode($response->raw_body, true)
        ;
    }
}
