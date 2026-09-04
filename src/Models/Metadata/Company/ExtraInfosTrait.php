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

namespace Splash\Connectors\Sellsy\Models\Metadata\Company;

use Splash\Connectors\Sellsy\Models\Metadata\Common\SocialUrls;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Companies Extras Fields: Legal IDs, Social Urls, GDPR Consents...
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
     * Legal information for France about the company.
     */
    #[
        Assert\Type(LegalFrance::class),
        Serializer\SerializedName("legal_france"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\SubResource(LegalFrance::class),
        SPL\Accessor(factory: "addLegalFrance"),
    ]
    public ?LegalFrance $legalFrance = null;

    /**
     * Add Social Urls
     */
    public function addSocialUrls(): void
    {
        $this->social = new SocialUrls();
    }

    /**
     * Add Legal France Information
     */
    public function addLegalFrance(): void
    {
        $this->legalFrance = new LegalFrance();
    }
}
