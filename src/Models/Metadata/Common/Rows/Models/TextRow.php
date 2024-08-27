<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Common\Rows\Models;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Common Class for Titles Rows & Comment Rows
 */
abstract class TextRow extends AbstractRow
{
    #[
        Assert\Type("string"),
        JMS\SerializedName("text"),
        JMS\Type("string"),
        JMS\Groups(array("Read")),
    ]
    public ?string $text = null;
}