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

namespace App\Entity\Common\Rows\Models;

use App\Entity\Common\Rows\Related;
use App\Entity\Taxe;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\MappedSuperclass()]
#[ORM\HasLifecycleCallbacks()]
abstract class ProductRow extends AbstractRow
{
    /**
     * Row's reference
     */
    #[
        Assert\Type("string"),
        ORM\Column(type: Types::STRING, nullable: true),
        Serializer\Groups("read")
    ]
    public ?string $reference = null;

    /**
     * Row's description
     */
    #[
        Assert\Type("string"),
        ORM\Column(type: Types::STRING, nullable: true),
        Serializer\Groups("read")
    ]
    public ?string $description = null;

    /**
     * Row's quantity
     */
    #[
        Assert\Type("string"),
        ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2),
        Serializer\Groups("read")
    ]
    public float $unit_amount = 0.0;

    /**
     * Row's quantity
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        ORM\Column(type: Types::STRING),
        Serializer\Groups("read")
    ]
    public string $quantity;

    /**
     * Row's quantity
     */
    #[
        Assert\NotNull,
        Assert\Type("integer"),
        ORM\Column(type: Types::INTEGER),
        Serializer\Groups("read")
    ]
    public int $taxId = 0;

    /**
     * Row's quantity
     */
    #[
        Serializer\Ignore()
    ]
    public ?Related $related = null;

    /**
     * Set Unit Amount as Float
     */
    public function setUnitAmount(string $value): void
    {
        $this->unit_amount = (float) $value;
    }

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
