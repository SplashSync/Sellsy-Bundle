<?php

namespace Splash\Connectors\Sellsy\Models\Metadata\Invoice;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Invoice Links
 */
trait LinksTraits
{
    /**
     * Invoice's Public Link.
     */
    #[
        JMS\SerializedName("public_link"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_URL, desc: "Invoice Public Link", group: "Links"),
        SPL\IsReadOnly(),
    ]
    public ?PublicLink $publicLink = null;

    /**
     * Invoice's PDF link.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("pdf_link"),
        JMS\Type("string"),
        JMS\Groups(array("Read")),
        SPL\Field(type: SPL_T_URL, desc: "Invoice PDF Link", group: "Links"),
        SPL\IsReadOnly(),
    ]
    public ?string $pdfLink = null;

    /**
     * Get Invoice Public Link Url
     */
    public function getPublicLink(): ?string
    {
        if (empty($this->publicLink) || empty($this->publicLink->enabled)) {
            return null;
        }

        return $this->publicLink->url ?: null;
    }
}