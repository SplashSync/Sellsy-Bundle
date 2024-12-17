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

use App\Entity\Common\Rows\Models\ProductRow;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table("invoice_rows")]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap(array(
    SingleRow::DATATYPE => SingleRow::class,
    CatalogRow::DATATYPE => CatalogRow::class,
))]
class SingleRow extends ProductRow
{
    public const DATATYPE = "single";
}
