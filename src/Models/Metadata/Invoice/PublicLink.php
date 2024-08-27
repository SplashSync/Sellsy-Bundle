<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Invoice;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class PublicLink
{
    /**
     * Is Public Link Enabled ?
     *
     * @var bool
     */
    #[
        Assert\NotNull,
        Assert\Type("bool"),
        JMS\SerializedName("enabled"),
        JMS\Type("bool"),
        SPL\Field(type: SPL_T_BOOL, desc: "Is Public Link Enabled ?"),
    ]
    public bool $enabled = false;

    /**
     * Public Link URL
     *
     * @var string
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("url"),
        JMS\Type("string"),
        SPL\Field(type: SPL_T_URL, desc: "Public Link URL"),
    ]
    public string $url = "";
}