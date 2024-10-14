<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Webhook;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sellsy Webhook Event
 */
class Event
{
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("id"),
        JMS\Type("string"),
        JMS\Groups(array("Required", "Read", "Write")),
        SPL\Field(desc: "Event ID"),
    ]
    public string $id;

    #[
        Assert\Type("boolean"),
        JMS\SerializedName("is_enabled"),
        JMS\Type("boolean"),
        SPL\Field(),
    ]
    public bool $isEnabled = true;

    #[
        Assert\Type("string"),
        JMS\SerializedName("channel"),
        JMS\Type("string"),
        JMS\Groups(array("Read")),
        SPL\Field(),
        SPL\IsReadOnly(),
    ]
    public ?string $channel = null;
}