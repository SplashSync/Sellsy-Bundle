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

namespace Splash\Connectors\Sellsy\Models\Actions;

use Splash\OpenApi\Dictionary\ActionOptions;
use Splash\OpenApi\Interfaces\Visitor\VisitorInterface;
use Splash\OpenApi\Models\Action\AbstractListAction;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SellsyListAction extends AbstractListAction
{
    /**
     * Option: append the search suffix to the collection uri
     */
    const SEARCH = "search";

    /**
     * Uri suffix of Sellsy search endpoints
     */
    const SEARCH_URI = "/search";

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault(self::SEARCH, true)
            ->setAllowedTypes(self::SEARCH, "bool")
        ;
    }

    /**
     * Resolve Collection Uri
     *
     * Sellsy searches its collections on a dedicated /search endpoint, while
     * the collection uri itself stays the creation one.
     */
    protected function getUri(
        VisitorInterface $visitor,
        ?string $filter = null,
        array $params = array()
    ): string {
        $collectionUri = parent::getUri($visitor, $filter, $params);

        return $this->getOption(self::SEARCH, true)
            ? $collectionUri.self::SEARCH_URI
            : $collectionUri
        ;
    }

    /**
     * Execute Collection Request
     */
    protected function executeRequest(
        VisitorInterface $visitor,
        ?string $filter = null,
        array $params = array()
    ): ?array {
        //====================================================================//
        // Resolve Collection Uri
        $collectionUri = $this->getUri($visitor, $filter, $params);
        //====================================================================//
        // Build Query Parameters
        $queryParams = array("filters" => array());
        if ($filter) {
            $queryParams["filters"][$this->getOption(ActionOptions::FILTER_KEY)] = $filter;
        }

        //====================================================================//
        // Execute Get Request
        return $visitor->getConnexion()->post($collectionUri, $queryParams);
    }

    /**
     * {@inheritdoc}
     */
    protected function extractData(VisitorInterface $visitor, array $rawResponse): array
    {
        //====================================================================//
        // Sellsy wraps its collections in a "data" key
        return parent::extractData($visitor, $rawResponse['data'] ?? array());
    }

    /**
     * {@inheritdoc}
     */
    protected function extractTotal(array $rawResponse, array $params = null): int
    {
        return $rawResponse['pagination']['total'] ?? parent::extractTotal($rawResponse, $params);
    }
}
