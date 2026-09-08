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

namespace Splash\Connectors\Sellsy\Models\Locator;

use Splash\Connectors\Sellsy\Services\Api\ScopesManager;
use Splash\Connectors\Sellsy\Services\Api\WebhooksManager;
use Symfony\Contracts\Service\Attribute\SubscribedService;

/**
 * Implement Sellsy Locator Access for Api Wide Services
 */
trait LocatorApiTrait
{
    /**
     * Get Sellsy Scopes Manager — Oauth2 scopes granted to the application.
     */
    #[SubscribedService(ScopesManager::class)]
    public function getScopesManager(): ScopesManager
    {
        return $this->get(ScopesManager::class);
    }

    /**
     * Get Sellsy Webhooks Manager — setup of the account change notifications.
     */
    #[SubscribedService(WebhooksManager::class)]
    public function getWebhooksManager(): WebhooksManager
    {
        return $this->get(WebhooksManager::class);
    }
}
