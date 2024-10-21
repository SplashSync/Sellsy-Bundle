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

namespace Splash\Connectors\Sellsy\Actions\Webhooks;

use Splash\Bundle\Models\AbstractConnector;
use Splash\Bundle\Models\Local\ActionsTrait;
use Splash\Connectors\Sellsy\Connector\SellsyConnector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class Setup extends AbstractController
{
    use ActionsTrait;

    //==============================================================================
    // WEBHOOKS CONFIGURATION
    //==============================================================================

    /**
     * Update User Connector WebHooks List
     *
     * @param Request             $request
     * @param TranslatorInterface $translator
     * @param AbstractConnector   $connector
     *
     * @return Response
     */
    public function __invoke(
        Request $request,
        TranslatorInterface $translator,
        AbstractConnector $connector
    ): Response {
        $result = false;
        //====================================================================//
        // Connector SelfTest
        if (($connector instanceof SellsyConnector) && $connector->selfTest()) {
            //====================================================================//
            // Update WebHooks Config
            $result = $connector->getLocator()->getWebhooksManager()->updateWebHooks();
        }
        //====================================================================//
        // Inform User
        $this->addFlash(
            $result ? "success" : "danger",
            $translator->trans(
                $result ? "admin.webhooks.setup.ok" : "admin.webhooks.setup.fail",
                array(),
                "SellsyBundle"
            )
        );
        //====================================================================//
        // Redirect Response
        /** @var string $referer */
        $referer = $request->headers->get('referer');
        if (empty($referer)) {
            return self::getDefaultResponse();
        }

        return new RedirectResponse($referer);
    }
}
