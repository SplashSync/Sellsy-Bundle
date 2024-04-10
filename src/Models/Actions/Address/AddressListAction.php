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

namespace Splash\Connectors\Sellsy\Models\Actions\Address;

use Splash\Connectors\Sellsy\Models\Metadata\Addresses\Addresses;
use Splash\OpenApi\ApiResponse;
use Splash\OpenApi\Models\Action\AbstractListAction;

class AddressListAction extends AbstractListAction
{
    /**
     * @inheritdoc
     */
    public function execute(string $filter = null, array $params = null): ApiResponse
    {
        //====================================================================//
        // Add Extra Params to Request
        $query = http_build_query($this->getQueryParameters($filter, $params));
        $query .= "&embed[]=invoicing_address";
        $query .= "&embed[]=delivery_address";

        //====================================================================//
        // Execute Get Request
        $rawResponse = $this->visitor->getConnexion()->get(
            $this->visitor->getCollectionUri()."?".$query,
            array()
        );
        if (null === $rawResponse) {
            return $this->getErrorResponse();
        }
        //====================================================================//
        // Extract Results
        $results = $this->extractData($rawResponse);
        //====================================================================//
        // Compute Meta
        $meta = array(
            'current' => count($results),
            'total' => $this->extractTotal($rawResponse, $params)
        );
        if (empty($this->options['raw'])) {
            $results["meta"] = $meta;
        }

        return new ApiResponse($this->visitor, true, $results, $meta);
    }

    /**
     * {@inheritdoc}
     */
    protected function extractData(array $rawResponse): array
    {
        /** @var Addresses[] $results */
        $results = array();

        foreach ($rawResponse['data'] as $company) {
            if (!empty($company['_embed']['invoicing_address'])) {
                $addressId = $company['_embed']['invoicing_address']['id'];
                $company['_embed']['invoicing_address']['id'] = sprintf("%s-%s", $addressId, $company['id']);
                $results[$addressId] ??= $this->visitor
                    ->getHydrator()
                    ->hydrate($company['_embed']['invoicing_address'], $this->visitor->getModel())
                ;
            }
            if (!empty($company['_embed']['delivery_address'])) {
                $addressId = $company['_embed']['delivery_address']['id'];
                $company['_embed']['delivery_address']['id'] = sprintf("%s-%s", $addressId, $company['id']);
                $results[$addressId] ??= $this->visitor
                    ->getHydrator()
                    ->hydrate($company['_embed']['delivery_address'], $this->visitor->getModel())
                ;
            }
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    protected function extractTotal(array $rawResponse, array $params = null): int
    {
        return $rawResponse['pagination']['total'] ?? parent::extractTotal($rawResponse, $params);
    }
}
