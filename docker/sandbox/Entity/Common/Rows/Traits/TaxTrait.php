<?php

namespace App\Entity\Common\Rows\Traits;

use App\Entity\Taxe;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sellsy Row Tax ID Storage
 */
trait TaxTrait
{
    /**
     * Row's Tax ID
     */
    #[
        Assert\NotNull,
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER),
        Serializer\Groups("read")
    ]
    public int $taxId = 0;

    /**
     * Verify received Tax ID Exits on DB
     */
    #[ORM\PrePersist()]
    #[ORM\PreUpdate()]
    public function validateTaxId(LifecycleEventArgs $event): void
    {
        //====================================================================//
        // No Tax ID
        if (!$this->taxId) {
            return;
        }
        //====================================================================//
        // Load Tax ID
        if (!$event->getObjectManager()->getRepository(Taxe::class)->find($this->taxId)) {
            throw new NotFoundHttpException(
                sprintf("Requested Tax ID %s was not found", $this->taxId)
            );
        }
    }
}