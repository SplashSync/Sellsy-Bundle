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

use Exception;
use Splash\Bundle\Models\AbstractConnector;
use Splash\Bundle\Models\Local\ActionsTrait;
use Splash\Connectors\Sellsy\Dictionary\WebhookArgs;
use Splash\Connectors\Sellsy\Dictionary\WebhookConfig;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Webmozart\Assert\Assert;

/**
 * Controller for Receiving Webhooks
 */
class Receive extends AbstractController
{
    use ActionsTrait;

    /**
     * Execute WebHook Actions
     *
     * @param Request           $request
     * @param AbstractConnector $connector
     *
     * @throws BadRequestHttpException|Exception
     *
     * @return JsonResponse
     */
    public function __invoke(
        Request $request,
        AbstractConnector $connector
    ): JsonResponse {
        //====================================================================//
        // For Sellsy Ping GET
        if ($request->isMethod('GET')) {
            return $this->prepareResponse();
        }

        //==============================================================================
        // Safety Check
        $notification = $this->verify($request);

        //====================================================================//
        // Walk on Known Webhook Configs
        foreach (WebhookConfig::all() as $whConfig) {
            if (!$whConfig->handle($notification)) {
                continue;
            }
            //==============================================================================
            // Detect Splash Changes Type
            $action = WebhookArgs::toSplashAction($notification[WebhookArgs::ACTION]);
            //==============================================================================
            // Commit Changes
            $connector->commit(
                $whConfig->getObjectType(),
                $notification[WebhookArgs::OBJECT_ID],
                $action,
                'Sellsy API',
                sprintf("Sellsy: %s %s", $whConfig->getObjectType(), ucfirst($action))
            );
        }

        return $this->prepareResponse();
    }

    /**
     * Verify Request & Extract Notification
     *
     * @throws Exception
     */
    private function verify(Request $request) : array
    {
        //====================================================================//
        // Verify Request is POST
        if (!$request->isMethod('POST')) {
            throw new BadRequestHttpException('Malformed or missing data');
        }
        //====================================================================//
        // Verify WebHook Comes from Sellsy
        $userAgent = $request->headers->get("user-agent");
        if (empty($userAgent) || !is_string($userAgent) || !str_starts_with($userAgent, "Sellsy")) {
            throw new BadRequestHttpException('Malformed or missing data');
        }
        //====================================================================//
        // Verify WebHook Type is Provided & is Valid
        $rawNotification = $request->get(WebhookArgs::INDEX);
        Assert::string($rawNotification);
        Assert::isArray($notification = json_decode($rawNotification, true));
        Assert::stringNotEmpty($notification[WebhookArgs::ACTION]);
        Assert::stringNotEmpty($notification[WebhookArgs::OBJECT_ID]);
        Assert::stringNotEmpty($notification[WebhookArgs::OBJECT_TYPE]);

        return $notification;
    }

    /**
     * Get Json Response
     */
    private function prepareResponse(int $status = 200): JsonResponse
    {
        return new JsonResponse(array('success' => true), $status);
    }
}
