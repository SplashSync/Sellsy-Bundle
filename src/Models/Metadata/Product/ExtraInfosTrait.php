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

namespace Splash\Connectors\Sellsy\Models\Metadata\Product;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Products Extras Fields: ...
 */
trait ExtraInfosTrait
{
    /**
     * Product's tax id.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("tax_id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_INT, desc: "Tax id"),
        SPL\IsRequired,
        SPL\Microdata("http://schema.org/Product", "taxID")
    ]
    public int $taxId;

    /**
     * Product's unit id.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("unit_id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_INT, desc: "Unit id"),
        SPL\IsRequired,
        SPL\Microdata("http://schema.org/Product", "sku")
    ]
    public int $unitId;

    /**
     * Product's category id.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("category_id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_INT, desc: "Category id"),
        SPL\Microdata("http://schema.org/Product", "category")
    ]
    public int $categoryId;

    /**
     * Product's accounting code id.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("accounting_code_id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_INT, desc: "Product's accounting code id"),
        SPL\Microdata("http://schema.org/AccountingService", "")
    ]
    public ?int $accountingCodeId;

    /**
     * Product's accounting purchase code id.
     */
    #[
        Assert\Type("integer"),
        JMS\SerializedName("accounting_purchase_code_id"),
        JMS\Type("integer"),
        SPL\Field(type: SPL_T_INT, desc: "Product's accounting purchase code id"),
        SPL\Microdata("http://schema.org/AccountingService", "")
    ]
    public ?int $accountingPurchaseCodeId;

    public function getAccountingCodeId(): ?int
    {
        return $this->accountingCodeId;
    }

    public function setAccountingCodeId(?int $accountingCodeId): self
    {
        if (0 === $accountingCodeId) {
            $accountingCodeId = null;
        }
        $this->accountingCodeId = $accountingCodeId;
        return $this;
    }

    public function getAccountingPurchaseCodeId(): ?int
    {
        return $this->accountingPurchaseCodeId;
    }

    public function setAccountingPurchaseCodeId(?int $accountingPurchaseCodeId): self
    {
        if (0 === $accountingPurchaseCodeId) {
            $accountingPurchaseCodeId = null;
        }
        $this->accountingPurchaseCodeId = $accountingPurchaseCodeId;
        return $this;
    }
}
