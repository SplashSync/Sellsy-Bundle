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

namespace Splash\Connectors\Sellsy\Models\Metadata\Payment;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sellsy Payment Amounts
 */
class Amounts
{
    /**
     * Payment total amount.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total"),
        JMS\Type("string"),
    ]
    public string $total = "";

    /**
     * Payment remaining amount.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("total"),
        JMS\Type("string"),
    ]
    public string $remaining = "";
}
