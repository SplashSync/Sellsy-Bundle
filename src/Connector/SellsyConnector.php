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

namespace Splash\Connectors\Sellsy\Connector;

use ArrayObject;
use Exception;
use Psr\Log\LoggerInterface;
use Splash\Bundle\Models\Connectors\GenericObjectMapperTrait;
use Splash\Bundle\Models\Connectors\GenericWidgetMapperTrait;
use Splash\Connectors\Sellsy\Actions\Webhooks\Receive;
use Splash\Connectors\Sellsy\Actions\Webhooks\Setup;
use Splash\Connectors\Sellsy\Form\SellsyEditForm;
use Splash\Connectors\Sellsy\Oauth2\PrivateClient;
use Splash\Connectors\Sellsy\Oauth2\SandboxClient;
use Splash\Connectors\Sellsy\Objects;
use Splash\Connectors\Sellsy\Services\SellsyLocator;
use Splash\Connectors\Sellsy\Widgets;
use Splash\Core\SplashCore as Splash;
use Splash\Metadata\Services\MetadataAdapter;
use Splash\OpenApi\Action;
use Splash\OpenApi\Connexion\JsonHalConnexion;
use Splash\OpenApi\Hydrator\Hydrator;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;
use Splash\Security\Oauth2\Model\AbstractOauth2Connector;
use Splash\Security\Oauth2\Services\Oauth2ClientManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Sellsy REST API Connector for Splash
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SellsyConnector extends AbstractOauth2Connector
{
    use GenericObjectMapperTrait;
    use GenericWidgetMapperTrait;

    /**
     * Objects Type Class Map
     *
     * @var array<string, class-string>
     */
    protected static array $objectsMap = array(
        "ThirdParty" => Objects\ThirdParty::class,
        "Address" => Objects\Address::class,
        "Product" => Objects\Product::class,
        "Invoice" => Objects\Invoice::class,
        "Webhook" => Objects\Webhook::class,
    );

    /**
     * Widgets Type Class Map
     *
     * @var array
     */
    protected static array $widgetsMap = array(
        "SelfTest" => Widgets\SelfTest::class,
    );

    /**
     * @var ConnexionInterface
     */
    private ConnexionInterface $connexion;

    /**
     * Object Hydrator
     *
     * @var Hydrator
     */
    private Hydrator $hydrator;

    /**
     * @var string
     */
    private string $metaDir;

    public function __construct(
        protected readonly MetadataAdapter   $metadataAdapter,
        protected readonly SellsyLocator   $locator,
        Oauth2ClientManager $oauth2ClientManager,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface          $logger
    ) {
        parent::__construct($oauth2ClientManager, $eventDispatcher, $logger);
    }

    /**
     * Setup Cache Dir for Metadata
     */
    public function setMetaDir(string $metaDir) : void
    {
        $this->metaDir = $metaDir."/metadata/sellsy";
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function ping() : bool
    {
        //====================================================================//
        // Safety Check => Verify Self-test Pass
        if (!$this->selfTest()) {
            return false;
        }

        //====================================================================//
        // Perform Ping Test
        return Action\Ping::execute($this->getConnexion(), "/scopes");
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function connect() : bool
    {
        //====================================================================//
        // Safety Check => Verify Self-test Pass
        if (!$this->selfTest()) {
            return false;
        }
        //====================================================================//
        // Sandbox Connect Test Always Pass
        if (!$this->isSandbox() && !$this->getTokenOrRefresh()) {
            return false;
        }
        //====================================================================//
        // Perform Connect Test
        if (!Action\Connect::execute($this->getConnexion(), "/scopes")) {
            return false;
        }
        //====================================================================//
        // Get Scopes Information
        if (!$this->getLocator()->getScopesManager()->fetchAccessScopes()) {
            return false;
        }
        //====================================================================//
        // Get List of Available Taxes
        if (!$this->getLocator()->getTaxManager()->fetchTaxesLists()) {
            return false;
        }
        //====================================================================//
        // Get List of Available Payment Methods
        if (!$this->getLocator()->getPaymentMethodsManager()->fetchMethodsLists()) {
            return false;
        }

        $this->updateConfiguration();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function informations(ArrayObject  $informations) : ArrayObject
    {
        //====================================================================//
        // Server General Description
        $informations->shortdesc = "Sellsy API";
        $informations->longdesc = "Splash Integration for Sellsy CRM";
        //====================================================================//
        // Company Informations
        $informations->company = "Sellsy";
        $informations->address = "50 avenue du Lazaret";
        $informations->zip = "17000";
        $informations->town = "LA ROCHELLE";
        $informations->country = "France";
        $informations->www = "https://sellsy.com/";
        $informations->email = "contact@sellsy.com";
        $informations->phone = "&nbsp;";
        //====================================================================//
        // Server Logo & Ico
        $informations->icoraw = Splash::file()->readFileContents(
            dirname(__FILE__, 2)."/Resources/public/img/favicon-32x32.png"
        );
        $informations->logourl = null;
        $informations->logoraw = Splash::file()->readFileContents(
            dirname(__FILE__, 2)."/Resources/public/img/favicon-192x192.png"
        );
        //====================================================================//
        // Server Informations
        $informations->servertype = "Sellsy CRM";
        $informations->serverurl = "www.sellsy.com";
        //====================================================================//
        // Module Informations
        $informations->moduleauthor = "Splash Official <www.splashsync.com>";
        $informations->moduleversion = "master";

        return $informations;
    }

    /**
     * {@inheritdoc}
     */
    public function selfTest() : bool
    {
        $config = $this->getConfiguration();
        //====================================================================//
        // Verify Webservice Url is Set
        //====================================================================//
        if (empty($config["WsHost"]) || !is_string($config["WsHost"])) {
            Splash::log()->err("Webservice Host is Invalid");

            return false;
        }
        //====================================================================//
        // Verify Api Key is Set
        //====================================================================//
        if (empty($config["apiKey"]) || !is_string($config["apiKey"])) {
            Splash::log()->err("Api Key is Invalid");

            return false;
        }

        return true;
    }

    //====================================================================//
    // Files Interfaces
    //====================================================================//

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function getFile(string $filePath, string $fileMd5): ?array
    {
        //====================================================================//
        // Safety Check => Verify Self-test Pass
        if (!$this->selfTest()) {
            return null;
        }

        return null;
    }

    //====================================================================//
    // Profile Interfaces
    //====================================================================//

    /**
     * Get Connector Profile Information
     *
     * @return array
     */
    public function getProfile() : array
    {
        return array(
            'enabled' => true,                                      // is Connector Enabled
            'beta' => false,                                        // is this a Beta release
            'type' => self::TYPE_HIDDEN,                            // Connector Type or Mode
            'name' => 'sellsy',                                     // Connector code (lowercase, no space allowed)
            'connector' => 'splash.connectors.sellsy',              // Connector Symfony Service
            'title' => 'profile.card.title',                        // Public short name
            'label' => 'profile.card.label',                        // Public long name
            'domain' => 'SellsyBundle',                             // Translation domain for names
            'ico' => '/bundles/sellsy/img/favicon-192x192.png',     // Public Icon path
            'www' => 'https://www.sellsy.com',                      // Website Url
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFormBuilderName() : string
    {
        $this->selfTest();

        return SellsyEditForm::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicActions() : array
    {
        return array(
            "index" => Receive::class,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getSecuredActions() : array
    {
        return array_merge_recursive(
            parent::getSecuredActions(),
            array(
                "webhooks" => Setup::class,
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectedTemplate() : string
    {
        return "@Sellsy/Profile/connected.html.twig";
    }

    //====================================================================//
    // Sellsy Connector Specific
    //====================================================================//

    /**
     * Check if Connector use Sandbox Mode
     *
     * @return bool
     */
    public function isSandbox(): bool
    {
        if ($this->getParameter("isSandbox", false)) {
            return true;
        }

        return false;
    }

    //====================================================================//
    // Open API Connector Interfaces
    //====================================================================//

    /**
     * Get Connector Api Connexion
     *
     * @throws Exception
     *
     * @return ConnexionInterface
     */
    public function getConnexion() : ConnexionInterface
    {
        //====================================================================//
        // Connexion already created
        if (isset($this->connexion)) {
            return $this->connexion;
        }
        //====================================================================//
        // Safety check
        if (!$this->selfTest()) {
            throw new \RuntimeException("Self-test fails... Unable to create API Connexion!");
        }
        if (!$client = $this->getOauth2Client()) {
            throw new \RuntimeException("Self-test fails... Unable to create API Client!");
        }
        //====================================================================//
        // Fetch Access Token
        $token = $this->getTokenOrRefresh();
        //====================================================================//
        // Setup Api Connexion
        $this->connexion = new JsonHalConnexion(
            $this->isSandbox() ? SandboxClient::ENDPOINT : PrivateClient::ENDPOINT,
            $client->getOAuth2Provider()->getHeaders($token)
        );

        return $this->connexion;
    }

    /**
     * @return Hydrator
     */
    public function getHydrator(): Hydrator
    {
        //====================================================================//
        // Configure Object Hydrator
        if (!isset($this->hydrator)) {
            $this->hydrator = new Hydrator($this->metaDir);
        }

        return $this->hydrator;
    }

    /**
     * @inheritDoc
     */
    public function getOauth2ClientCode(): string
    {
        return $this->isSandbox() ? SandboxClient::CODE : PrivateClient::CODE;
    }

    /**
     * Get Splash Metadata Adapter
     */
    public function getMetadataAdapter(): MetadataAdapter
    {
        return $this->metadataAdapter;
    }

    /**
     * Get Sellsy Services Locator
     */
    public function getLocator(): SellsyLocator
    {
        return $this->locator->configure($this);
    }

    public function getDefaultCurrency(): string
    {
        return "EUR"; // TODO: Get Default Currency from Sellsy and/or User local settings
    }
}
