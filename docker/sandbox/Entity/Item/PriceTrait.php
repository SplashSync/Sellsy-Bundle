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

namespace App\Entity\Item;

use App\Entity\Taxe;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait PriceTrait
{
    /**
     * Product's wholesale price in Splash format
     */
    #[
        Assert\Type("string"),
        ORM\Column(type: Types::STRING),
        Serializer\Groups(array("read", "write")),
    ]
    public string $referencePrice = "0.0";

    /**
     * Product's reference price excluding taxes
     */
    #[
        ORM\Column(type: Types::FLOAT),
        Serializer\Groups("read"),
    ]
    public float $referencePriceTaxesExc = 0.0;

    /**
     * Product's reference price excluding taxes
     */
    #[
        ORM\Column(type: Types::FLOAT),
        Serializer\Groups("read"),
    ]
    public float $referencePriceTaxesInc = 0.0;

    /**
     * Is Reference Price Taxes Free
     */
    #[
        Assert\Type("boolean"),
        ORM\Column(type: Types::BOOLEAN),
        Serializer\Groups(array("read", "write")),
    ]
    public bool $isReferencePriceTaxesFree = true;

    /**
     * Product's purchase amount
     */
    #[
        Assert\Type("string"),
        ORM\Column(type: Types::STRING),
        Serializer\Groups(array("read", "write")),
    ]
    public string $purchaseAmount = "0.0";

    /**
     * Product's currency code
     */
    #[
        Assert\Type("string"),
        ORM\Column(type: Types::STRING),
        Serializer\Groups(array("read", "write")),
    ]
    public ?string $currency = "EUR";

    /**
     * Product's tax ID.
     */
    #[
        Assert\Type("integer"),
        ORM\Column(nullable: false),
        Serializer\Groups(array("read", "write")),
    ]
    public int $taxId = 0;

    #[ORM\PreUpdate()]
    public function updatePriceFields(PreUpdateEventArgs $event): void
    {
        //====================================================================//
        // No Tax ID
        if (!$this->taxId) {
            return;
        }
        //====================================================================//
        // Identify VAT Rate
        $tax = $event->getObjectManager()->getRepository(Taxe::class)->find($this->taxId);
        if (!$tax) {
            throw new NotFoundHttpException(
                sprintf("Requested Tax ID %s was not found", $this->taxId)
            );
        }
        //====================================================================//
        // Compute VAT Amounts
        if ($this->isReferencePriceTaxesFree) {
            $this->referencePriceTaxesExc = $this->referencePrice;
            $this->referencePriceTaxesInc = $this->referencePrice;
            $this->referencePriceTaxesInc += $this->referencePrice * $tax->rate / 100;
        } else {
            $this->referencePriceTaxesInc = $this->referencePrice;
            $this->referencePriceTaxesExc = $this->referencePrice;
            $this->referencePriceTaxesExc += $this->referencePrice * $tax->rate / 100;
        }
    }
}
