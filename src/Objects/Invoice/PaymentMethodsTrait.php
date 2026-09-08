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

namespace Splash\Connectors\Sellsy\Objects\Invoice;

/**
 * Sellsy Invoices Payment Methods Choices
 */
trait PaymentMethodsTrait
{
    /**
     * Build Payment Methods Fields
     */
    protected function buildPaymentMethodsFields(): void
    {
        //====================================================================//
        // Safety Check - Account Payment Methods are Known
        $choices = $this->connector->getLocator()->getPaymentMethodsManager()->getChoices();
        if (empty($choices)) {
            return;
        }
        //====================================================================//
        // Payment Method (Only add choices for Selector)
        self::fieldsFactory()->get("method@payments")?->setChoices($choices);
    }
}
