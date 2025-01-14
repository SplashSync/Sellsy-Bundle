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

use App\Entity\Company;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        Serializer\Groups(array("read", "write")),
    ]
    public array $related = array();

    //    #[
    //        Assert\Type("array"),
    //        ORM\Column(type: Types::JSON),
    //        Serializer\Groups(array("read")),
    //    ]
    //    public ?array $payments = null;

    #[
        Assert\Type("array"),
        ORM\Column(type: Types::JSON),
        Serializer\Groups(array("read")),
    ]
    public array $amounts = array();

    #[
        ORM\ManyToOne(targetEntity: Company::class),
    ]
    public ?Company $customer = null;

    /**
     * Update Customer relation based on received Relation Array
     */
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateCustomer(LifecycleEventArgs $event): void
    {
        //====================================================================//
        // Extract Company from Related
        $customerId = null;
        foreach ($this->related as $related) {
            if ('company' === $related['type']) {
                $customerId = $related['id'];

                break;
            }
        }

        //====================================================================//
        // Ensure Customer Id is Valid
        $current = $this->customer->id ?? null;
        if ($current && $customerId && ($current == $customerId)) {
            return;
        }

        //====================================================================//
        // Identify New Customer
        $company = $event->getObjectManager()->getRepository(Company::class)->find($customerId);
        if (!$company) {
            throw new NotFoundHttpException(
                sprintf("Target Company %s not found", $customerId)
            );
        }

        //====================================================================//
        $this->customer = $company;
    }
}
