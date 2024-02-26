<?php

namespace Splash\Connectors\Sellsy\Models\Actions;

use Splash\OpenApi\Models\Action\AbstractListAction;

class SellsyListAction extends AbstractListAction
{
    /**
     * {@inheritdoc}
     */
    protected function extractData(array $rawResponse): array
    {
        return parent::extractData($rawResponse['data'] ?? array());
    }

    /**
     * {@inheritdoc}
     */
    protected function extractTotal(array $rawResponse, array $params = null): int
    {
        return $rawResponse['pagination']['total'] ?? parent::extractTotal($rawResponse, $params);
    }
}