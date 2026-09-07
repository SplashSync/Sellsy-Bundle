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

use DateTime;
use Splash\Core\Dictionary\SplFields;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Splash\Templates\Common\CommonFields;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Company Metadata Fields
 */
trait MetadataTrait
{
    #[
        Assert\Type("boolean"),
        Serializer\SerializedName("is_archived"),
        Serializer\Groups(SplGroups::DEFAULT),
        SPL\Field(type: SplFields::BOOL, desc: "Is Company Archived", group: "Meta"),
    ]
    public bool $isArchived;

    #[
        Assert\Type("datetime"),
        Serializer\SerializedName("created"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Template(CommonFields::DATE_CREATED),
        SPL\IsReadOnly,

    ]
    public DateTime $created;

    #[
        Assert\Type("datetime"),
        Serializer\SerializedName("updated_at"),
        Serializer\Groups(array(SplGroups::READ)),
        SPL\Template(CommonFields::DATE_MODIFIED),
        SPL\IsReadOnly,
    ]
    public DateTime $updatedAt;
}
