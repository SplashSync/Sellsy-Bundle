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

namespace App\Serializer;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Metadata\CollectionOperationInterface;
use App\Entity\SellsyObjectInterface;
use ArrayObject;
use InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    public const FORMAT = 'json';

    /**
     * @var DenormalizerInterface|NormalizerInterface
     */
    private DenormalizerInterface|NormalizerInterface $decorated;

    /**
     * @param NormalizerInterface $decorated
     */
    public function __construct(NormalizerInterface $decorated)
    {
        if (!$decorated instanceof DenormalizerInterface) {
            throw new InvalidArgumentException(sprintf('The decorated normalizer must implement the %s.', DenormalizerInterface::class));
        }

        $this->decorated = $decorated;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = array()): bool
    {
        //====================================================================//
        // A Paginator is a collection: only Sellsy ones are ours to reshape.
        // Claiming the others would hand them to the item normalizer, which
        // would then look for an "id" on the Paginator itself.
        if ($data instanceof Paginator) {
            return (self::FORMAT === $format) && self::isManagedObject($context["resource_class"] ?? "");
        }

        return $this->decorated->supportsNormalization($data, $format, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function normalize(
        mixed $object,
        ?string $format = null,
        array $context = array()
    ): ArrayObject|array|string|int|float|bool|null {
        //====================================================================//
        //  Not a Sellsy Resource: let the decorated normalizer do its job
        if (!self::isManagedObject($context["resource_class"] ?? "")) {
            return $this->decorated->normalize($object, $format, $context);
        }
        //====================================================================//
        //  Collection Normalizer
        if ($object instanceof Paginator) {
            $data = array();
            //====================================================================//
            // The collection key comes from the resource class, not from its
            // items: an empty collection has none, and used to be answered
            // under its own class name.
            $resourceClass = $context["resource_class"];
            $parent = is_a($resourceClass, SellsyObjectInterface::class, true)
                ? $resourceClass::getCollectionIndex()
                : null
            ;
            foreach ($object as $index => $obj) {
                $data[$index] = $this->decorated->normalize($obj, $format, $context);
            }

            return $parent
                ? array($parent => $data, 'pagination' => $this->getPagination($object))
                : $data;
        }
        //====================================================================//
        //  Api Platform 3 dropped context["operation_type"]: the operation
        //  object itself tells whether we are on a collection or on an item.
        $operation = $context["operation"] ?? null;
        //====================================================================//
        //  Collection Normalizer
        if ($operation instanceof CollectionOperationInterface) {
            return $this->decorated->normalize($object, $format, $context);
        }
        //====================================================================//
        //  Item Normalizer
        if ($operation && ($object instanceof SellsyObjectInterface)) {
            return ($object::getItemIndex() && !isset($context["api_attribute"]))
                ? array($object::getItemIndex() => $this->decorated->normalize($object, $format, $context))
                : $this->decorated->normalize($object, $format, $context);
        }

        return $this->decorated->normalize($object, $format, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = array()
    ): bool {
        return $this->decorated->supportsDenormalization($data, $type, $format, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize(mixed $data, string $class, ?string $format = null, array $context = array()): mixed
    {
        //====================================================================//
        //  Check if Resource is Sbo Resource
        if (!self::isManagedObject($class)) {
            return $data;
        }

        return $this->decorated->denormalize($data, $class, $format, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedTypes(?string $format): array
    {
        return $this->decorated->getSupportedTypes($format);
    }

    /**
     * {@inheritDoc}
     */
    public function setSerializer(SerializerInterface $serializer): void
    {
        if ($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }

    /**
     * Check if Object is managed by Sbo Serializer
     *
     * @param class-string $className
     *
     * @return bool
     */
    private static function isManagedObject(string $className): bool
    {
        return (class_exists($className) && is_subclass_of($className, SellsyObjectInterface::class));
    }

    /**
     * Get API Pagination Array
     */
    private function getPagination(Paginator $paginator): array
    {
        return array(
            "limit" => $paginator->getItemsPerPage(),
            "count" => $paginator->count(),
            "total" => $paginator->getTotalItems(),
            "offset" => ($paginator->getCurrentPage() - 1) * $paginator->getItemsPerPage(),
        );
    }
}
