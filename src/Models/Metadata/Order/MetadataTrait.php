<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Order;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

trait MetadataTrait
{
    /**
     * Order's creation date.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("created"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_DATETIME, desc: "Order Creation Date"),
    ]
    public string $created;

    /**
     * Order's assigned staff ID.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("assigned_staff_id"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_INT, desc: "ID of the assigned staff member"),
    ]
    public int $assignedStaffId;

    /**
     * Order's invoicing address id.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("invoicing_address_id"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_INT, desc: "Invoicing address"),
    ]
    public int $invoincingAddressId;

    /**
     * Order's delivery address id.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("delivery_address_id"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_INT, desc: "Delivery address"),
    ]
    public int $deliveryAddressId;

    /**
     * Order's contact id.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("contact_id"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_INT, desc: "Delivery address"),
    ]
    public ?int $contactId;

    /**
     * Order's rate category id.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("rate_category_id"),
        JMS\Groups(array("Read", "List")),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_INT, desc: "Rate Category applied on document"),
    ]
    public int $rateCategoryId;
}