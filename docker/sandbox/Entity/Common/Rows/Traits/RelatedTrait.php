<?php

namespace App\Entity\Common\Rows\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Common\Rows\Models\Related;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * Sellsy Row Related Item Link
 */
trait RelatedTrait
{
    /**
     * Row's Related Item
     */
    #[
        Serializer\Groups(array("read", "write")),
    ]
    private ?Related $related = null;

    /**
     * Row's Related Item ID
     */
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $relatedId = null;

    /**
     * Row's Related Item Type
     */
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $relatedType = null;

    /**
     * Set Related Item
     */
    public function setRelated(?array $related): void
    {
        if (empty($related)) {
            $this->related = $this->relatedId = $this->relatedType = null;

            return;
        }

        $this->relatedId = (int) $related["id"] ?? 0;
        $this->relatedType = (string) $related["type"] ?? null;
    }

    /**
     * Get Related Item
     */
    public function getRelated(): ?Related
    {
        if ($this->relatedId && $this->relatedType) {
            $related = new Related();
            $related->id = $this->relatedId;
            $related->type = $this->relatedType;

            return $related;
        }

        return null;
    }
}