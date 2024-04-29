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

namespace Splash\Connectors\Sellsy\Models\Metadata\Contact;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class ContactSync
{
    #[
        Assert\Type("boolean"),
        JMS\SerializedName("mailchimp"),
        JMS\Type("boolean"),
        SPL\Field(type: SPL_T_BOOL, desc: "[Sync] Activate the mailchimp synchronization"),
    ]
    public bool $mailchimp = false;

    #[
        Assert\Type("boolean"),
        JMS\SerializedName("mailjet"),
        JMS\Type("boolean"),
        SPL\Field(type: SPL_T_BOOL, desc: "[Sync] Activate the mailjet synchronization"),
    ]
    public bool $mailjet = false;

    #[
        Assert\Type("boolean"),
        JMS\SerializedName("simplemail"),
        JMS\Type("boolean"),
        SPL\Field(type: SPL_T_BOOL, desc: "[Sync] Activate the simplemail synchronization"),
    ]
    public bool $simplemail = false;
}
