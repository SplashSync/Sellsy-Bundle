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
use Splash\Connectors\Sellsy\Models\Metadata\Common\Relation;
use Splash\Connectors\Sellsy\Models\Metadata\Payment;
use Splash\Metadata\Attributes as SPL;
use Splash\Models\Helpers\ObjectsHelper;
use Symfony\Component\Validator\Constraints as Assert;

trait RelationsTrait
{
    /**
     * @var Relation[]
     */
    #[
        Assert\Type("array"),
        JMS\SerializedName("related"),
        JMS\Groups(array("Read", "Write", "Required")),
        JMS\Type("array<".Relation::class.">"),
    ]
    public array $related = array();

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
        foreach ($this->related ?? array() as $related) {
            if ("company" === $related->type) {
                $relation = $related;

                break;
            }
        }

        return $relation ? ObjectsHelper::encode("ThirdParty", (string) $relation->id) : null;
    }

    /**
     * Set First Related Company
     */
    public function setCustomer(?string $objectId): static
    {
        //====================================================================//
        // Ensure objectId is not null before continuing
        if (!$objectId = ObjectsHelper::id((string) $objectId)) {
            Splash::log()->err("Customer ID cannot be null.");

            return $this;
        }
        //====================================================================//
        // Search for existing Company Relation
        $companiesRelations = array_filter($this->related, fn ($rel) => "company" === $rel->type);
        $relation = array_shift($companiesRelations);

        //====================================================================//
        // Compare with New Value
        if (($relation instanceof Relation) && ($relation->id == $objectId)) {
            return $this;
        }

        //====================================================================//
        // Update company relation
        $relation ??= new Relation();
        $relation->type = "company";
        $relation->id = (int) $objectId;
        // Update company relations
        $this->related = array($relation);

        return $this;
        //            // Remove any old company relations
        //            $this->related = array_filter($this->related, fn ($rel) => "company" !== $rel->type);
    }
}
