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

namespace Splash\Connectors\Sellsy\Widgets;

use Splash\Bundle\Models\AbstractStandaloneWidget;
use Splash\Connectors\Sellsy\Connector\SellsyConnector;
use Splash\Core\SplashCore as Splash;

/**
 * Sellsy Config SelfTest
 */
class SelfTest extends AbstractStandaloneWidget
{
    /**
     * {@inheritdoc}
     */
    public static array $options = array(
        "Width" => self::SIZE_DEFAULT,
        'UseCache' => true,
        'CacheLifeTime' => 1,
    );

    /**
     * {@inheritdoc}
     */
    protected static string $name = "Server SelfTest";

    /**
     * {@inheritdoc}
     */
    protected static string $description = "Results of your Server SelfTests";

    /**
     * {@inheritdoc}
     */
    protected static string $ico = "fa fa-info-circle";

    /**
     * Class Constructor
     *
     * @param SellsyConnector $connector
     */
    public function __construct(private SellsyConnector $connector)
    {
    }

    /**
     * Return requested Customer Data
     *
     * @param array $parameters Widget Inputs Parameters
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function get(array $parameters = null): ?array
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();

        //====================================================================//
        // Setup Widget Core Information
        //====================================================================//

        $this->setTitle($this->getName());
        $this->setIcon($this->getIcon());

        //====================================================================//
        // Build Intro Text Block
        //====================================================================//
        $this->buildIntroBlock();

        //====================================================================//
        // Build SelfTest Results Block
        //====================================================================//
        $this->connector->selfTest();
        $this->buildNotificationsBlock();

        //====================================================================//
        // Set Blocks to Widget
        $blocks = $this->blocksFactory()->render();
        if ($blocks) {
            $this->setBlocks($blocks);
        }

        //====================================================================//
        // Publish Widget
        return $this->render();
    }

    /**
     * Block Building - Text Intro
     */
    private function buildIntroBlock(): void
    {
        //====================================================================//
        // Into Text Block
        $this->blocksFactory()->addTextBlock("This widget summarize SelfTest of your SellsyConnector Connector Config");
    }

    /**
     * Block Building - Notifications Parameters
     */
    private function buildNotificationsBlock(): void
    {
        //====================================================================//
        // Get Log
        $log = Splash::log();
        //====================================================================//
        // If test was passed
        if (empty($log->err)) {
            $this->blocksFactory()->addNotificationsBlock(array("success" => "Self-Test Passed!"));
        }
        //====================================================================//
        // Add Error Notifications
        foreach ($log->err as $text) {
            $this->blocksFactory()->addNotificationsBlock(array("error" => $text));
        }
        //====================================================================//
        // Add Warning Notifications
        foreach ($log->war as $text) {
            $this->blocksFactory()->addNotificationsBlock(array("warning" => $text));
        }
        //====================================================================//
        // Add Success Notifications
        foreach ($log->msg as $text) {
            $this->blocksFactory()->addNotificationsBlock(array("success" => $text));
        }
        //====================================================================//
        // Add Debug Notifications
        foreach ($log->deb as $text) {
            $this->blocksFactory()->addNotificationsBlock(array("info" => $text));
        }
    }
}
