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

namespace Splash\Connectors\Sellsy\Models\Metadata\Contact;

use Splash\Connectors\Sellsy\Models\Metadata\Contact;
use Splash\Models\Objects\ObjectsTrait;

/**
 * Temporary Storage for Links between Contact & Companies
 */
class CompanyLink
{
    use ObjectsTrait;

    /**
     * Parent Contact ID
     */
    private int $parentId;

    public function __construct(
        Contact $contact,
        private readonly array $data
    ) {
        $this->parentId = (int) $contact->id;
    }

    /**
     * Get Target Company ID
     */
    public function getCompanyId(): ?int
    {
        return $this->data['id'] ?? null;
    }

    /**
     * Get Target Company Object ID
     */
    public function getObjectId(): ?string
    {
        return self::objects()->encode("ThirdParty", (string) $this->getCompanyId());
    }

    /**
     * Is Company Main Contact
     */
    public function isMain(): bool
    {
        return ($this->parentId == ($this->data["main_contact_id"] ?? "none"));
    }

    /**
     * Is Company Invoicing Contact
     */
    public function isInvoicing(): bool
    {
        return ($this->parentId == ($this->data["invoicing_contact_id"] ?? "none"));
    }

    /**
     * Is Company Dunning Contact
     */
    public function isDunning(): bool
    {
        return ($this->parentId == ($this->data["dunning_contact_id"] ?? "none"));
    }
}
