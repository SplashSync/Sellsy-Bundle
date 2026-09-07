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

namespace App\ApiPlatform;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\HttpOperation;
use Attribute;

/**
 * Sellsy Search Operation
 *
 * Sellsy reads its collections with a POST on /{resource}/search, filters
 * travelling in the request body.
 *
 * Same as a GetCollection — collection provider, pagination, normalization —
 * with POST as http method. GetCollection being final, and having no
 * constructor argument for the method, this extends HttpOperation directly.
 *
 * Search filters are ignored: the sandbox always answers the whole collection.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class SearchCollection extends HttpOperation implements CollectionOperationInterface
{
    public function __construct(string $uriTemplate)
    {
        parent::__construct(
            method: "POST",
            uriTemplate: $uriTemplate,
            //====================================================================//
            // A Post neither reads nor answers 200 by default: this one does
            // both, it is a collection read that happens to use POST.
            status: 200,
            read: true,
            //====================================================================//
            // The request body carries search filters, never an entity:
            // nothing to deserialize, nothing to write.
            deserialize: false,
            write: false,
        );
    }
}
