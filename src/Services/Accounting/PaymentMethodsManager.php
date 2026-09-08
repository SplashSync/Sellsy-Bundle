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

namespace Splash\Connectors\Sellsy\Services\Accounting;

use Exception;
use Splash\Connectors\Sellsy\Connector\SellsyConnector;
use Splash\Connectors\Sellsy\Interfaces\SellsyConnectorAwareInterface;
use Splash\Connectors\Sellsy\Models\Connector\SellsyConnectorAwareTrait;
use Splash\Core\Client\Splash;

/**
 * Manage Sellsy Payment Methods
 */
class PaymentMethodsManager implements SellsyConnectorAwareInterface
{
    use SellsyConnectorAwareTrait;

    /**
     * List of Sellsy Payment Methods
     *
     * @var array<string, array>
     */
    private array $methods = array();

    /**
     * Default of Payment Method ID
     *
     * @var null|int
     */
    private ?int $default = null;

    /**
     * List of Payment Methods Associations
     *
     * @var array<string, int>
     */
    private array $associations = array();

    /**
     * Configure with Current API Connexion Settings
     */
    public function configure(SellsyConnector $connector): static
    {
        $this->connector = $connector;
        $methods = $connector->getParameter("PaymentMethods", array());
        $this->methods = is_array($methods) ? $methods : array();
        $default = $connector->getParameter("PaymentMethodDefault");
        $this->default = is_integer($default) ? $default : null;
        $associations = $connector->getParameter("PaymentMethodsAssociations", array());
        $this->associations = is_array($associations) ? $associations : array();

        return $this;
    }

    /**
     * Get Sellsy Payment from API
     */
    public function fetchMethodsLists(): bool
    {
        //====================================================================//
        // Get Lists of Available Taxes from Api
        try {
            $response = $this->connector->getConnexion()->get("/payments/methods", array('limit' => "100"));
        } catch (Exception $e) {
            return Splash::log()->report($e);
        }
        if (!is_array($response) || !is_array($response['data'])) {
            return false;
        }
        //====================================================================//
        // Reformat results
        $methods = array_combine(
            array_map(static function (array $taxItem) {
                return $taxItem["id"];
            }, $response['data']),
            $response['data']
        );
        //====================================================================//
        // Store in Connector Settings
        $this->connector->setParameter("PaymentMethods", $methods);
        if (empty($this->connector->getParameter("PaymentMethodsCodes"))) {
            $this->connector->setParameter("PaymentMethodsCodes", array());
        }

        return true;
    }

    /**
     * Get Payment Method Label from ID
     */
    public function getTranslatedLabel(?int $methodId): ?string
    {
        //====================================================================//
        // Ensure this Method Exists in Configuration
        if (!$method = $this->methods[$methodId] ?? null) {
            return null;
        }
        //====================================================================//
        // Search for Associated Methods
        if ($label = $this->findAssociationById($methodId)) {
            return $label;
        }

        //====================================================================//
        // No Translation, uses Label
        return $method["label"] ?? null;
    }

    /**
     * Get Payment Method ID from Label
     */
    public function getIdFromLabel(?string $methodName): ?int
    {
        return $this->findAssociationByName($methodName)
            ?? $this->findMethodByName($methodName)
            ?? $this->getDefaultMethodId()
        ;
    }

    /**
     * Get All Payment Methods as Splash Field Choices
     *
     * Keyed by the label Splash exchanges, i.e. the association when one is
     * configured, the Sellsy label otherwise.
     *
     * @return array<string, string>
     */
    public function getChoices(): array
    {
        $choices = array();
        foreach (array_keys($this->methods) as $methodId) {
            if (!$label = $this->getTranslatedLabel((int) $methodId)) {
                continue;
            }
            $choices[$label] = $label;
        }

        return $choices;
    }

    /**
     * Get All Sellsy Payment Methods, as stored on the Account
     *
     * @return array<string, array>
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Get the Method used when Splash did not identify the received one
     */
    public function getDefaultMethodLabel(): ?string
    {
        return $this->getTranslatedLabel($this->default);
    }

    /**
     * Get Payment Method Name from Sellsy Method ID
     */
    private function findAssociationById(?int $methodId): ?string
    {
        if (empty($methodId)) {
            return null;
        }

        return array_search($methodId, $this->associations, true) ?: null;
    }

    /**
     * Get Payment Method ID from Associations
     */
    private function findAssociationByName(?string $methodName): ?int
    {
        if (empty($methodName)) {
            return null;
        }

        return $this->associations[$methodName] ?? null;
    }

    /**
     * Get Payment Method ID from Method Name
     */
    private function findMethodByName(?string $methodName): ?int
    {
        if (empty($methodName)) {
            return null;
        }

        foreach ($this->methods as $method) {
            if ($method["label"] === $methodName) {
                return $method["id"];
            }
        }

        return null;
    }

    /**
     * Get Default Payment Method ID
     */
    private function getDefaultMethodId(): ?int
    {
        return $this->default;
    }
}
