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

use App\Entity\Common\Rows\Models\AbstractRow;
use App\Entity\Common\Rows\Models\ProductRow;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait RowsAwareTrait
{
    /**
     * @var AbstractRow[]
     */
    #[
        Assert\Type("array<".AbstractRow::class.">"),
        Assert\NotNull,
        ORM\Column(),
        Serializer\Groups("read")
    ]
    public array $rows = array();

    /**
     * @return ProductRow[]
     */
    public function getProductRows(): array
    {
        return array_filter($this->rows, function (AbstractRow $row) {
            return $row instanceof ProductRow;
        });
    }
}
