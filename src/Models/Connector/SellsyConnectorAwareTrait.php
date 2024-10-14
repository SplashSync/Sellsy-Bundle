<?php

namespace Splash\Connectors\Sellsy\Models\Connector;

use Splash\Connectors\Sellsy\Connector\SellsyConnector;

trait SellsyConnectorAwareTrait
{
    /**
     * Currently Used Sellsy Connector
     */
    private SellsyConnector $connector;

    /**
     * Configure with Current API Connexion Settings
     */
    public function configure(SellsyConnector $connector): static
    {
        $this->connector = $connector;

        return $this;
    }
}