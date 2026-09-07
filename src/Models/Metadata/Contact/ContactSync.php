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

use Splash\Core\Dictionary\SplFields;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class ContactSync
{
    #[
        Assert\Type("boolean"),
        Serializer\SerializedName("mailchimp"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::BOOL, desc: "[Sync] Activate the mailchimp synchronization"),
    ]
    public ?bool $mailchimp = false;

    #[
        Assert\Type("boolean"),
        Serializer\SerializedName("mailjet"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::BOOL, desc: "[Sync] Activate the mailjet synchronization"),
    ]
    public ?bool $mailjet = false;

    #[
        Assert\Type("boolean"),
        Serializer\SerializedName("simplemail"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::BOOL, desc: "[Sync] Activate the Simple Mail synchronization"),
    ]
    public ?bool $simplemail = false;
}
