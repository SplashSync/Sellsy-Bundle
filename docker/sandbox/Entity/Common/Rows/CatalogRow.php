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

namespace App\Entity\Common\Rows;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class CatalogRow extends SingleRow
{
    public const DATATYPE = "catalog";

    //    /**
    //     * Row Related Service or Product.
    //     */
    //    #[
    //        Assert\Type(Related::class),
    //        JMS\SerializedName("related"),
    //        JMS\Type(Related::class),
    //        SPL\Field(type: SPL_T_ID."::Product", desc: "Catalog Product ID"),
    //        SPL\Microdata("http://schema.org/Product", "productID"),
    //    ]
}
