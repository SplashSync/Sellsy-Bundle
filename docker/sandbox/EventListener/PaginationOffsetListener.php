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

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Translate Sellsy Pagination to Api Platform Pagination
 *
 * Sellsy walks its collections with an offset, when Api Platform expects a
 * page number: the offset is converted before the request is handled.
 */
#[AsEventListener(event: KernelEvents::REQUEST, priority: 128)]
class PaginationOffsetListener
{
    /**
     * Sellsy Offset Query Parameter
     */
    const OFFSET = "offset";

    /**
     * Sellsy Items per Page Query Parameter
     */
    const LIMIT = "limit";

    /**
     * Api Platform Page Query Parameter
     */
    const PAGE = "page";

    /**
     * Default Number of Items per Page
     */
    const DEFAULT_LIMIT = 30;

    public function __invoke(RequestEvent $event): void
    {
        //====================================================================//
        // Only Main Requests, with an Offset
        $request = $event->getRequest();
        if (!$event->isMainRequest() || !$request->query->has(self::OFFSET)) {
            return;
        }
        //====================================================================//
        // Convert Offset to Page Number
        $limit = max(1, $request->query->getInt(self::LIMIT, self::DEFAULT_LIMIT));
        $offset = max(0, $request->query->getInt(self::OFFSET));
        $query = $request->query->all();
        unset($query[self::OFFSET]);
        $query[self::PAGE] = 1 + intdiv($offset, $limit);
        //====================================================================//
        // Api Platform reads its filters from the raw query string, so both
        // the parameters bag and the query string have to be rewritten.
        $request->query->replace($query);
        $request->server->set("QUERY_STRING", http_build_query($query));
    }
}
