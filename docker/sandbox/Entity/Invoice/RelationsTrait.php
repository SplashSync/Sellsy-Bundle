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

namespace App\Entity\Invoice;

use App\Entity\Common\Rows\Related;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Splash\Client\Splash;
use Splash\Connectors\Sellsy\Models\Metadata\Common\Relation;
use Splash\Models\Helpers\ObjectsHelper;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait RelationsTrait
{
    /**
     * Invoice's Linked Objects
     */
    #[
        Assert\NotNull,
        Assert\Type("array"),
        Assert\NotBlank,
        Assert\All(
            new Assert\Collection(
                fields: array(
                    "id" => new Assert\Type("integer"),
                    "type" => new Assert\Choice(array("company", "individual", "contact", "opportunity"))
                )
            )
        ),
        ORM\Column(type: Types::JSON, nullable: false),
        Serializer\Groups("read")
    ]
    public array $related = array();

    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON),
        Serializer\Groups(array("read")),
    ]
    public ?array $payments = null;

    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON),
        Serializer\Groups(array("read")),
    ]
    public array $amounts = array();

    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON),
    ]
    public ?array $customer = null;

    public function get_customer(): ?string
    {
        $relation = null;

        foreach ($this->related as $related) {
            if ('company' === $related['type']) {
                $relation = $related;

                break;
            }
        }

        return $relation ? ObjectsHelper::encode('ThirdParty', $relation->id) : null;
    }

    public function set_customer(?string $customerId): static
    {
        //====================================================================//
        // Ensure objectId is not null before continuing
        if (!$customerId = ObjectsHelper::id((string) $customerId)) {
            Splash::log()->err("Customer ID cannot be null.");

            return $this;
        }

        //====================================================================//
        // Search for existing Company Relation
        $companiesRelations = array_filter($this->related, fn ($rel) => "company" === $rel->type);
        $relation = array_shift($companiesRelations);

        //====================================================================//
        // Compare with New Value
        if (($relation instanceof Relation) && ($relation->id == $customerId)) {
            return $this;
        }

        if ($relation) {
            $relation->id = $customerId;
        } else {
            $relation = new Related();
            $relation->id = $customerId;
            $relation->type = "company";
            $this->related[] = $relation;
        }

        return $this;
    }
}
