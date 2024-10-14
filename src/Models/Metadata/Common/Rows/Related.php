<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Common\Rows;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Splash\Models\Helpers\ObjectsHelper;

/**
 * Sellsy row related data
 */
class Related
{
    /**
     * Row Relation ID.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("id"),
        JMS\Type("integer"),
    ]
    public int $id;

    /**
     * Row Relation Type.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("type"),
        JMS\Type("string"),
        JMS\Groups(array("Read", "Write")),
    ]
    public string $type;

    /**
     * Row Declination ID.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("declination_id"),
        JMS\Type("integer"),
    ]
    public ?int $declinationId = null;

    public function toSplash(): ?string
    {
        return ObjectsHelper::encode("Product", $this->id);
    }

    public function fromSplash(?string $productId): static
    {
        $this->id = ObjectsHelper::id($productId);
        $this->type ??= "product";
        $this->declinationId = null;

        return $this;
    }
}