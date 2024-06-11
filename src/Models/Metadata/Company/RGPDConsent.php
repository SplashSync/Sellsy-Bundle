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

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class RGPDConsent
{
    /**
     * Email consent.
     *
     * @var bool
     */
    #[
        Assert\Type("boolean"),
        JMS\SerializedName("email"),
        JMS\Type("boolean"),
        SPL\Field(type: SPL_T_BOOL, desc: "Is Email consent given"),
        SPL\PreferRead(),
    ]
    public bool $email;

    /**
     * SMS consent.
     *
     * @var bool
     */
    #[
        Assert\Type("boolean"),
        JMS\SerializedName("sms"),
        JMS\Type("boolean"),
        SPL\Field(type: SPL_T_BOOL, desc: "Is SMS consent given"),
        SPL\PreferRead(),
    ]
    public bool $sms;

    /**
     * Phone consent.
     *
     * @var bool
     */
    #[
        Assert\Type("boolean"),
        JMS\SerializedName("phone"),
        JMS\Type("boolean"),
        SPL\Field(type: SPL_T_BOOL, desc: "Is Phone consent given"),
        SPL\PreferRead(),
    ]
    public bool $phone;

    /**
     * Postal Mail consent.
     *
     * @var bool
     */
    #[
        Assert\Type("boolean"),
        JMS\SerializedName("postal_mail"),
        JMS\Type("boolean"),
        SPL\Field(type: SPL_T_BOOL, desc: "Is Postal Mail consent given"),
        SPL\PreferRead(),
    ]
    public bool $postalMail;

    /**
     * Custom consent.
     *
     * @var bool
     */
    #[
        Assert\Type("boolean"),
        JMS\SerializedName("custom"),
        JMS\Type("boolean"),
        SPL\Field(type: SPL_T_BOOL, desc: "Is Custom consent given"),
        SPL\PreferRead(),
    ]
    public bool $custom;
}
