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

use Splash\Connectors\Sellsy\Models\Metadata\Common\SocialUrls;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contacts Extras Fields: Social Urls, Synchronization...
 */
trait ExtraInfosTrait
{
    /**
     * Social media information for the company.
     */
    #[
        Assert\Type(SocialUrls::class),
        Serializer\SerializedName("social"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\SubResource(),
        SPL\Accessor(factory: "addSocialUrls"),
    ]
    public ?SocialUrls $social = null;

    /**
     * Contact Synchronisation Options.
     */
    #[
        Assert\Type(ContactSync::class),
        Serializer\SerializedName("sync"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\SubResource(targetClass: ContactSync::class),
        SPL\Accessor(factory: "addContactSync"),
    ]
    public ?ContactSync $sync = null;

    /**
     * Add Social Urls
     */
    public function addSocialUrls(): void
    {
        $this->social = new SocialUrls();
    }

    /**
     * Add Contact Synchronization Options
     */
    public function addContactSync(): void
    {
        $this->sync = new ContactSync();
    }
}
