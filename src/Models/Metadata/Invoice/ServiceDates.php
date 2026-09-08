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

namespace Splash\Connectors\Sellsy\Models\Metadata\Invoice;

use Splash\Core\Dictionary\SplFields;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

class ServiceDates
{
    /**
     * Service Start Date
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("date"),
        Serializer\SerializedName("start"),
        Serializer\Groups(SplGroups::DEFAULT),
        Serializer\Context(array(DateTimeNormalizer::FORMAT_KEY => "Y-m-d")),
        SPL\Field(type: SplFields::DATE, desc: "Service Start Date"),
    ]
    public string $start = "";

    /**
     * Service End Date
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("date"),
        Serializer\SerializedName("end"),
        Serializer\Groups(SplGroups::DEFAULT),
        Serializer\Context(array(DateTimeNormalizer::FORMAT_KEY => "Y-m-d")),
        SPL\Field(type: SplFields::DATE, desc: "Service End Date"),
    ]
    public string $end = "";
}
