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

namespace Splash\Connectors\Sellsy\Services;

use Splash\Connectors\Sellsy\Interfaces\SellsyConnectorAwareInterface;
use Splash\Connectors\Sellsy\Models\Connector\SellsyConnectorAwareTrait;
use Splash\Connectors\Sellsy\Models\Locator as LocatorTraits;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Webmozart\Assert\Assert;

/**
 * Sellsy Connector Service Locator
 *
 * Each service is exposed by a getter of the Locator traits: the subscribed
 * services list is built from their attributes, and the current connector is
 * injected on the fly.
 */
class SellsyLocator implements ServiceSubscriberInterface, SellsyConnectorAwareInterface
{
    use SellsyConnectorAwareTrait;
    use ServiceMethodsSubscriberTrait;
    use LocatorTraits\LocatorApiTrait;
    use LocatorTraits\LocatorAccountingTrait;
    use LocatorTraits\LocatorThirdPartyTrait;

    /**
     * Fetch a Service, check its type, and configure it with the current connector
     *
     * @template T of object
     *
     * @param class-string<T> $serviceClass
     *
     * @return T
     */
    protected function get(string $serviceClass): object
    {
        Assert::isInstanceOf(
            $service = $this->container->get($serviceClass),
            $serviceClass
        );
        if ($service instanceof SellsyConnectorAwareInterface) {
            $service->configure($this->connector);
        }

        return $service;
    }
}
