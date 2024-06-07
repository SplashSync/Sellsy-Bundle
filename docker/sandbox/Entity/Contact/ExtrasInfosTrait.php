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

namespace App\Entity\Contact;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contact Extras Fields for Sandbox: Social Urls, Synchronization...
 */
trait ExtrasInfosTrait
{
    /**
     * Contact social networks links
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true),
        Serializer\Groups("read"),
    ]
    public ?array $social = array(
        "facebook" => null,
        "twitter" => null,
        "linkedin" => null,
        "viadeo" => null
    );

    /**
     * Contact Synchronization
     */
    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON, nullable: true),
        Serializer\Groups("read"),
    ]
    public ?array $sync = array(
        "mailchimp" => false,
        "mailjet" => false,
        "simplemail" => false,
    );
}
