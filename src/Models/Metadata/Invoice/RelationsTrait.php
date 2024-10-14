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

use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\Sellsy\Models\Metadata\Payment;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Relation;
use Splash\Metadata\Attributes as SPL;
use Splash\Models\Helpers\ObjectsHelper;
use Symfony\Component\Validator\Constraints as Assert;

trait RelationsTrait
{
    /**
     * @var null|Relation[]
     */
    #[
        Assert\Type("array"),
        JMS\SerializedName("related"),
        JMS\Groups(array("Read")),
        JMS\Type("array<".Relation::class.">"),
    ]
    public ?array $related = null;

    /**
     * @var Payment[]|null
     */
    #[
        Assert\Type("array"),
        JMS\SerializedName("payments"),
        JMS\Groups(array("Read")),
        JMS\Type("array<".Payment::class.">"),
        SPL\ListResource(targetClass: Payment::class),
        SPL\Manual(read: false, write: true),
    ]
    public ?array $payments = null;

    /**
     * @var Amounts
     */
    #[
        Assert\Type("array"),
        JMS\SerializedName("amounts"),
        JMS\Groups(array("Read")),
        JMS\Type(Amounts::class),
        SPL\SubResource(Amounts::class, write: false)
    ]
    public Amounts $amounts;


    #[
        SPL\Field(
            type: SPL_T_ID.IDSPLIT."ThirdParty",
            desc: "Invoice Customer Company"
        ),
    ]
    public ?array $customer = null;

    /**
     * Get First Related Company
     */
    public function getCustomer(): ?string
    {
        $relation = null;

        //====================================================================//
        // Identify First Company
        foreach ($this->related ?? array() as $related)
        {
            if ($related->type === "company") {
                $relation = $related;
            }
        }

        return $relation ? ObjectsHelper::encode("ThirdParty", $relation->id) : null;
    }

    /**
     * Get First Related Company
     */
    public function setCustomer(?string $objectId): static
    {
        $relation = null;

        //====================================================================//
        // Check if Company already exists
        foreach ($this->related ?? array() as $related) {
            if ("company" === $related->type && $related->id === ObjectsHelper::id($objectId)) {
                $relation = $related;
            }
        }

        // If Not Found, Create Company Relation
        $this->customer = $relation ? ObjectsHelper::encode("ThirdParty", ObjectsHelper::id($objectId)) : null;

        return $this;
    }
}
