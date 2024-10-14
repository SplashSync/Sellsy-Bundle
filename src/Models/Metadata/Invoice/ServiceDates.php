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

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
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
        JMS\SerializedName("start"),
        JMS\Type("date"),
        SPL\Field(type: SPL_T_DATE, desc: "Service Start Date"),
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
        JMS\SerializedName("end"),
        JMS\Type("date"),
        SPL\Field(type: SPL_T_DATE, desc: "Service End Date"),
    ]
    public string $end = "";
}
