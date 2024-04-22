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
use Splash\Connectors\Sellsy\Models\Metadata\Common\SocialUrls;
use Splash\Metadata\Attributes as SPL;
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
        JMS\SerializedName("social"),
        JMS\Type(SocialUrls::class),
        SPL\SubResource(),
        SPL\Accessor(factory: "addSocialUrls"),
    ]
    public ?SocialUrls $social = null;

    //    /**
    //     * Contact Synchronisation Options.
    //     */
    //    #[
    //        Assert\Type(ContactSync::class),
    //        JMS\SerializedName("sync"),
    //        JMS\Type(ContactSync::class),
    //        SPL\SubResource(targetClass: ContactSync::class, write: false),
    //        SPL\Accessor(factory: "addLegalFrance"),
    //    ]
    //    public ?ContactSync $sync = null;

    /**
     * Add Social Urls
     */
    public function addSocialUrls(): void
    {
        $this->social = new SocialUrls();
    }

    //    /**
    //     * Add Legal France Information
    //     */
    //    public function addLegalFrance(): void
    //    {
    //        $this->legalFrance = new LegalFrance();
    //    }
}
