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

use DateTime;
use Splash\Metadata\Attributes as SPL;
use Splash\OpenApi\Dictionary\SerializerGroups as SplGroups;
use Splash\Templates\InvoiceFields;
use Symfony\Component\Serializer\Attribute as Serializer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

trait DatesTrait
{
    /**
     * Invoice's date.
     */
    #[
        Assert\NotNull,
        Assert\Type("date"),
        Serializer\SerializedName("date"),
        Serializer\Context(array(DateTimeNormalizer::FORMAT_KEY => "Y-m-d")),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE, SplGroups::LIST, SplGroups::REQUIRED)),
        SPL\Template(InvoiceFields::DATE),
        SPL\IsRequired,
    ]
    public DateTime $date;

    /**
     * Invoice's due date.
     *
     * Nullable: Sellsy computes it from the payment terms, and leaves it
     * empty as long as none is set on the document.
     */
    #[
        Assert\Type("date"),
        Serializer\SerializedName("due_date"),
        Serializer\Context(array(DateTimeNormalizer::FORMAT_KEY => "Y-m-d")),
        Serializer\Groups(array(SplGroups::READ, SplGroups::WRITE)),
        SPL\Template(InvoiceFields::DATE_DUE),
    ]
    public ?DateTime $dueDate = null;
}
