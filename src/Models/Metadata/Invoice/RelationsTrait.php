<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Invoice;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Relation;
use Splash\Models\Helpers\ObjectsHelper;

trait RelationsTrait
{

    /**
     * @var Relation[]|null
     */
    #[
        Assert\Type("array"),
        JMS\SerializedName("related"),
        JMS\Groups(array("Read")),
        JMS\Type("array<".Relation::class.">"),
    ]
    public ?array $related = null;


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
        // TODO
//        foreach ($this->related ?? array() as $related)
//        {
//            if ($related->type === "company") {
//                $relation = $related;
//            }
//        }
//
//        return $relation ? ObjectsHelper::encode("ThirdParty", $relation->id) : null;

        return $this;
    }
}