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

interface RowInterface
{
    public const DATATYPE = "single";

    /**
     * Get Row ID
     *
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * Get Row Type
     *
     * @return null|string
     */
    public function getType(): ?string;
}
