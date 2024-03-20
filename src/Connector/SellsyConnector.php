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
use Splash\Connectors\Sellsy\Oauth2\PrivateClient;
use Splash\Connectors\Sellsy\Oauth2\SandboxClient;
use Splash\Connectors\Sellsy\Objects;
use Splash\Connectors\Sellsy\Widgets;
use Splash\Core\SplashCore as Splash;
use Splash\Metadata\Services\MetadataAdapter;
use Splash\OpenApi\Action;
use Splash\OpenApi\Connexion\JsonHalConnexion;
use Splash\OpenApi\Hydrator\Hydrator;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;
use Splash\Security\Oauth2\Form\PrivateAppConfigurationForm;
use Splash\Security\Oauth2\Model\AbstractOauth2Connector;
use Splash\Security\Oauth2\Model\Oauth2AwareConnector;
use Splash\Security\Oauth2\Services\Oauth2ClientManager;
//use Splash\Connectors\ReCommerce\Actions\Master;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Sellsy REST API Connector for Splash
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
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
        //        "Address" => Objects\ThirdParty::class,
        //        "Product" => Objects\ThirdParty::class,
        //        "Invoice" => Objects\ThirdParty::class,
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
        Oauth2ClientManager $oauth2ClientManager,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface          $logger
    ) {
        parent::__construct($oauth2ClientManager, $eventDispatcher, $logger);
    }

    //    public function __construct(
    //        private Oauth2ClientManager $oauth2ClientManager,
    //        EventDispatcherInterface $eventDispatcher,
    //        LoggerInterface $logger
    //    ){
    //        parent::__construct($eventDispatcher, $logger);
    //    }

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

        //        $this->setParameter(Oauth2AwareConnector::ACCESS_TOKEN, array(
        //            "token_type" => "Bearer",
        //            "access_token" => "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0ZjYwOTQwMi05NjcyLTRkZTgtODI1NS00MGMyZGU5NmM3M2IiLCJqdGkiOiJjNWE3NDlmYWRlZWQ3ODJjZGZlYTYwMDg2OTkwNTA5NjIzMGJmMjRkODRlNmEzNjcwYzNlMWJmZmI2NzA2NjJlZmY1ZGE1ZDVlNGZhZTRlMyIsImlhdCI6MTcwODk0MTYzMC41OTc4NiwibmJmIjoxNzA4OTQxNjMwLjU5Nzg3MSwiZXhwIjoxNzA5MDI4MDMwLjU3ODMyMiwic3ViIjoiMzYwZGRhMGItMmM0NS00ZTllLWFhYWItMWY0YjFiMzc4MmJmIiwic2NvcGVzIjpbImFsbCIsImNybSIsImNvbXBhbmllcyIsImNvbXBhbmllcy5yZWFkIiwiY29tcGFuaWVzLndyaXRlIiwiY29udGFjdHMiLCJjb250YWN0cy5yZWFkIiwiY29udGFjdHMud3JpdGUiLCJpbmRpdmlkdWFscyIsImluZGl2aWR1YWxzLnJlYWQiLCJpbmRpdmlkdWFscy53cml0ZSIsIm9wcG9ydHVuaXRpZXMiLCJvcHBvcnR1bml0aWVzLnJlYWQiLCJvcHBvcnR1bml0aWVzLndyaXRlIiwicGhvbmVjYWxscyIsInBob25lY2FsbHMucmVhZCIsInBob25lY2FsbHMud3JpdGUiLCJjYWxlbmRhcnMiLCJjYWxlbmRhcnMucmVhZCIsImNhbGVuZGFycy53cml0ZSIsImNvbW1lbnRzIiwiY29tbWVudHMucmVhZCIsImNvbW1lbnRzLndyaXRlIiwiZW1haWxzIiwiZW1haWxzLnJlYWQiLCJlbWFpbHMuc2V0dGluZ3MiLCJhY3Rpdml0aWVzIiwiYWN0aXZpdGllcy5yZWFkIiwiYWN0aXZpdGllcy53cml0ZSIsImN1c3RvbS1hY3Rpdml0aWVzIiwiY3VzdG9tLWFjdGl2aXRpZXMucmVhZCIsImN1c3RvbS1hY3Rpdml0aWVzLndyaXRlIiwidGFza3MiLCJ0YXNrcy5yZWFkIiwidGFza3Mud3JpdGUiLCJpbnZvaWNpbmciLCJpbnZvaWNpbmcucmVhZCIsInByaW1lcyIsInByaW1lcy5yZWFkIiwiZGlzY291bnQtaW5jbC10YXhlcyIsImRpc2NvdW50LWluY2wtdGF4ZXMucmVhZCIsImRpc2NvdW50LWluY2wtdGF4ZXMud3JpdGUiLCJhY2NvdW50aW5nIiwiYWNjb3VudGluZy1jb2RlcyIsImFjY291bnRpbmctY29kZXMucmVhZCIsImFjY291bnRpbmctY29kZXMud3JpdGUiLCJhY2NvdW50aW5nLWVudHJpZXMiLCJhY2NvdW50aW5nLWVudHJ5LnJlYWQiLCJhY2NvdW50aW5nLWVudHJ5LndyaXRlIiwidGF4ZXMiLCJ0YXhlcy5yZWFkIiwidGF4ZXMud3JpdGUiLCJlc3RpbWF0ZXMiLCJlc3RpbWF0ZXMucmVhZCIsImVzdGltYXRlcy53cml0ZSIsImludm9pY2VzIiwiaW52b2ljZXMucmVhZCIsImludm9pY2VzLndyaXRlIiwiY3JlZGl0LW5vdGVzIiwiY3JlZGl0LW5vdGVzLnJlYWQiLCJjcmVkaXQtbm90ZXMud3JpdGUiLCJkb2N1bWVudC1sYXlvdXRzIiwiZG9jdW1lbnQtbGF5b3V0cy5yZWFkIiwicmF0ZS1jYXRlZ29yaWVzIiwicmF0ZS1jYXRlZ29yaWVzLnJlYWQiLCJyYXRlLWNhdGVnb3JpZXMud3JpdGUiLCJvcmRlcnMiLCJvcmRlcnMucmVhZCIsIm9yZGVycy53cml0ZSIsInN1YnNjcmlwdGlvbnMiLCJzdWJzY3JpcHRpb25zLndyaXRlIiwic3Vic2NyaXB0aW9ucy5yZWFkIiwibW9kZWxzIiwibW9kZWxzLnJlYWQiLCJtb2RlbHMud3JpdGUiLCJjdXN0b20tZmllbGRzIiwiY3VzdG9tLWZpZWxkcy5yZWFkIiwiY3VzdG9tLWZpZWxkcy53cml0ZSIsInNtYXJ0LXRhZ3MiLCJzbWFydC10YWdzLnJlYWQiLCJzbWFydC10YWdzLndyaXRlIiwib2NyIiwib2NyLnJlYWQiLCJsaXN0aW5ncyIsImxpc3RpbmdzLnJlYWQiLCJsaXN0aW5ncy53cml0ZSIsInNlYXJjaCIsInNlYXJjaC5yZWFkIiwiYWNjZXNzLXRva2VucyIsImFjY2Vzcy10b2tlbnMucmVhZCIsImFjY2Vzcy10b2tlbnMud3JpdGUiLCJ3ZWJob29rcyIsIndlYmhvb2tzLnJlYWQiLCJ3ZWJob29rcy53cml0ZSIsInBheW1lbnRzIiwicGF5bWVudHMucmVhZCIsInBheW1lbnRzLndyaXRlIiwic3RhZmYiLCJzdGFmZnMucmVhZCIsInN0YWZmcy53cml0ZSIsImFjY291bnQiLCJhY2NvdW50cy5yZWFkIiwiYWNjb3VudHMud3JpdGUiLCJzY29wZXMiLCJzY29wZXMucmVhZCIsImZpbGVzIiwiZmlsZXMucmVhZCIsImZpbGVzLndyaXRlIiwiY2xpZW50cyIsImNsaWVudHMucmVhZCIsImNsaWVudHMud3JpdGUiLCJhZHllbiIsImFkeWVuLnJlYWQiLCJhZHllbi53cml0ZSIsImFwaS12MSIsImNhdGFsb2ciLCJpdGVtcy5yZWFkIiwiaXRlbXMud3JpdGUiLCJub3RpZmljYXRpb25zIiwibm90aWZpY2F0aW9ucy5yZWFkIiwibm90aWZpY2F0aW9ucy53cml0ZSJdLCJ1c2VyVHlwZSI6InN0YWZmIiwidXNlcklkIjoyOTM5MDksImNvcnBJZCI6MTA1MDA5LCJjb3JwTmFtZSI6IlNQTEFTSFNZTkMiLCJmaXJzdE5hbWUiOiJQYXF1aWVyIiwibGFzdE5hbWUiOiJCZXJuYXJkIiwibGFuZ3VhZ2UiOiJmciIsImVtYWlsIjoiY29udGFjdEBzcGxhc2hzeW5jLmNvbSJ9.Nt1t-3dxFroFjuyxNoLfbwT6SNzZU1X3pKWcA3I36MvDfZoweMKXOTluWjuHPFDjh0-tlCopau24-LSbiFzMzM8SGSp43_MByMCVyTBqbhGNIJFKu0ULrTKm-u53lbveYGz8UCZXKl2dbCQKjz48pTnvz5ijqoq60lmJgv939UbS5O7QfcSaQi2QG5JlR7CDvmTqR3vPTW_LruYGowNnvQ3r8YCgi0oYW42f7C9w20eWZyCwvxIykEupDQOMZVdSJlZiipmz2t-mH1c5x-_36CYVCRwwNeK0HwA6NjjEyE2-VE2feFfAfoiOefvw7YR09c-OJ4gT50MWcL6oEjcDAA",
        //            "refresh_token" => "def502000c213372c5952cfd52daebc861c96f84f5bf72f5adf9b2110b4bb3bb02e27941f1c23bc2a10df0bcc5084f188da6fffa8c06c07bcd500ce079406bdc33ea5a3066354c94ca72c59f6471e99db79affcbfcf14263f06f75d60793ba54e4b7765e5df041170306c484eca19ffa25241ddbe9f5e677236b4468b69c811318691cf4db2401087892cfb57f6fe8b587fe553519978730cd6bb93cdd57bf4f642629de057c321dde3caff2ba7aacd055b565fdc002148530c1ab1b64218151bd619d4217eac87a9e2ff480c9892a2dcfbc21d1f5374eef0bd54685e175193a328de14c942fc21e9ab437a9c9afdb0192a28b695ce986b85ffcc8daa3b16829142456d8bc79c295dbedd311ebf04bb003c7bba499d70c816a317a31d4aecd4e15e05242aa16db9f9b720bce0d433f0afaf281c6be0dd34e9fa03e20bda0702b10d4789d6f66956db3067ac0e5e4878e78f8fcd80e6bd2b1deccec3b76ad35ed4006fd0ed29cbfdc10e81f169c47fa6e2f21896a11bb7a9e748ae5070230f65a4a0e6e8329493e29d6d7e118b2bba06d5ccc4832bfb278e7275ceabb82e9950da409526d92a5841fc1360a6a8e93eae4302ce0ea523480562c99eaf32280859c777338ecfe01cc9a0cc4ebd05eba9bef937d195ccdb2c21b889d8057bce227dbc81d61d6f2e9f66a529978196b39f5ea4be912df482fef94d101a2ad2935747f53a032c707ab8e05d951d4b968eb71a8333d60306d133eed37d2f81560aec3cc2939de32a03ee030eeb9f090677d3d11ad80b0af328cf8d2f181b88179bf6a44f36183db5c09cb934f2db8b8faa7624f94a2c6e34d353aca59ab88fcb4b705139c4a3398acf60357e589cf0fd496da2be5de3efd19ee29239d6784d830764a44d28f7e8748c91735628c6279166f1787902680dc70adaa7a2cd48f5ddb46a5aafecd8bba3816fa54e7bac50b699b71790fd2df20b5d2be222685a629da85cdfb027a480218044c77b2fd367e968ede44de43018a9be9dad76d9ac187fb399cb7d5f2e96941ae4b1601dbe74e6aad44fee7c56c470307f8b332a31cceb10c0f983aeadce244c2d586ef6abbfcbb117f149315f5e90ca47d9633799d680ae53f04a0b119d7ceaaa6598956a9a392c5d3ad2947d1c99d8371f49d8a898aeacbe8c1e9f9126ad1b3302df3602fbbfbac528f00d5b328ba5e117e4e06bc7e66adbe67ae3093e90383b15247356c41cfefb9e0f9710eac1b8a6a3f6afb13bde8647138d1654dbb41ba7676989bbd11af0779cede4985e8b16331560dc05a56749c3358d1527f6e1aae1ee2c67c8e2e6acecd998a36116a1070d4493bdd010af6bbd189bf76556bd6466d18b4d3af7f1a48286f47b1eb43580d077043d72785cf4bf23a181a98dd25fc466b920c23b372d5a1eb54979579b26fb83d675c2968fa93480e2a1a7120933778a84be3d34b3714d545b5cdcd5d75bbd0c655ecf5fde25aca14dbd6c06c6b6c777b01efefccabd5471d52e5399d3ce7b58bf940e97a05b30fc773a9c23d92dd261f06b00f0e7eed175491b5978d310822cfc8306c5ad5b0ed1510652a418437f4ff3cbd8de8cb1b98ed2dbdbdbcac65b1fe956cd6742884ff65a95c0e98e51a17c8be09ec96588564ab6bee87d107bf228459256554201f849edba56ff97234c1038fcb8863fbf84bda9ff32e2c8fe46f1c41bb6d63a2f3ad57af321b075b289e6237a3471b40d0d4854989be538124bbdfedb0ff7670d1036bf4ff56f9b86168aff44ed2f9c9b3655f2f63ffe2ece2428834bb2ac13d7c199ecb831420720de2933e594ef773880fd6667347a66fb197671a9d01f7f5c1b653463f3ea6927a6474fb7d46c554df3bd8459f432dcf6a3d8fea0da449882525b72b0a8ed3b101c5ee2b4d643ccc7b186df75fa3f9135c0c7cb3ee0cf2a32e246c62251d9ea40192098833d5a17fb9e4652d47f12e1f1d2177224b6cb57c5238cb18fff676092e3cecad6fa1b76e3e74778cf1cf44c69619820b985296ff8b591fd195f036eeccf2315e19402dfc8f447fc4ce7b782f45a53e49585d3757dbcc5de9ec5e3be31920575d913f7cd3c800a86a9124d4308c5dce016aebb9f94dcd836cbe3b1be0e04848a9d5444f81a17c6a379fe8923f0222e16401d74bbe7a29c9b627a6a62891f8b3b32b7d523a173e739b64bb7334e07c0b38972fa3b716c17824e7314864416140140ca4e3e093137b16f21b2ab170cdf5038e64fa9a290e5e15be714fd52bb046a77d8c23144532a01ec32ffd9cc74712ff316ce314ef76de12c00dac8c1dfef693a0d43e28f36de9388d76a13b5844bb8a99afadfbea6b94c258e9bfa45b105b67235cda9ba0571256c47954fca6f01032a1b0b0672629761f258fc61775e8eb0b6ea77648c06d311e34a8369868df17e974e20da6c4142cdfef9db6de2cf247269a1163d455ab6b743841ec16fc2e76a1c292eb91dcbfccf83534c8b83e02fd9fec9b377611c9d2bdae65997838fd154cbc533f8e8aff713dbf248bbed43aa2e9ee5cd2dc4046af55dbf2e55fbefbaef1338b7a0f46a323c0580350d48f654672abd40ff5ccd990479c1e96e91f5f3443e6ae3f11c335071b4703290dcaab8225aad5a8aa1a47acf63ee72eb64a3a692d5969443542866c278ad3d28324df21d1e5d9d3f2c800799e4e0e645cc5f4853f27036e80c18c2a3380fc7b227dec65b6bea91b095d6e90147811ad70ddc53627a842bd3f24c0562feffb516af97ca8e5fe30fcd2ae2e051aa7f038adf84797625db2d0d1523d88a644cba03ddd4af032b25befebd6e4a7c436822c7bfad4b0cd40d8ff30d1d4d40186e94efcfd2ef044d961379e5dec3e39f97479fcc8049bd9b629afda0c25d73085c00b5626808e3ce7877871c9b0aff1e967cbf0c2ea6d5b6b9d58dc03b635ec9e8373223fdf7f75b3893cd16ab0783f4ff306af5ce7c8e8fff8b40da93344be83fb62f6460728cc1da778bfec1a2d6236d981e0305c0a171f6e60dc1b98e51b75325c13b3af61b441061c5b2d6d1c726820aafda7fc573c5da5d3bee6a730767dade5d03d7e5ef7ca24d2b233d756a24cf21e5a9845753f93f0e51579729a1575a0d491ebb21ec10daa947e214b5a975228605bd82da8e05ae645945691751cf8aca9b0c5e0b971416ce6e2de8c5e4396984c6eb23e91d3687f3a7ec25b1867fef7fc566fbe311bc9964240e2aa47f2f9d52119a020a9fb9fb838d61c894d7eb471b76df170828b682ff6c01b921b30b8540d1e5a70e60c342beda2e441130c71ef270c",
        //            "expires" => 1709028030
        //        ));
        //        $this->updateConfiguration();
        //        dump($this->getTokenOrRefresh(true));

        //        $this->oauth2ClientManager->connect($this);
        //        dd($this);

        //        return true;
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
        if ($this->isSandbox() && !$this->getTokenOrRefresh()) {
            return false;
        }

        //====================================================================//
        // Perform Connect Test
        return Action\Connect::execute($this->getConnexion(), "/scopes");
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

        return PrivateAppConfigurationForm::class;
    }

    //    /**
    //     * {@inheritdoc}
    //     */
    //    public function getMasterAction(): ?string
    //    {
    //        return \Splash\Security\Oauth2\Actions\Master::class;
    //    }

    /**
     * {@inheritdoc}
     */
    public function getPublicActions() : array
    {
        return array(
            //            "webhook" => Master::class,
            //            "connect" => \Splash\Security\Oauth2\Actions\Connect::class,
        );
    }

    //    /**
    //     * {@inheritdoc}
    //     */
    //    public function getSecuredActions() : array
    //    {
    //        return array(
    //            "connect" => \Splash\Security\Oauth2\Actions\Connect::class,
    //        );
    //    }

    //====================================================================//
    // ReCommerce Connector Specific
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
}
