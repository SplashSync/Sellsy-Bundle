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

use App\Entity\Common\Rows\SellsyRow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Sales Documents Rows Denormalizer
 *
 * Sellsy identifies an updated row by its id, whereas Api Platform never
 * denormalizes identifiers: the stored row is loaded here, so that a write
 * updates it instead of replacing it.
 */
final class RowDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    /**
     * Context flag, so that the decorated denormalizer is called only once
     */
    private const ALREADY_CALLED = "SELLSY_ROW_DENORMALIZED";

    public function __construct(
        private readonly EntityManagerInterface $manager
    ) {
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
        return is_array($data) && (SellsyRow::class === $type) && empty($context[self::ALREADY_CALLED]);
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = array()): mixed
    {
        $context[self::ALREADY_CALLED] = true;
        //====================================================================//
        // Load the Stored Row, so that received values are merged into it
        if (is_array($data) && ($rowId = $data["id"] ?? null)) {
            unset($data["id"]);
            if ($row = $this->manager->getRepository(SellsyRow::class)->find($rowId)) {
                $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $row;
            }
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function getSupportedTypes(?string $format): array
    {
        return array(SellsyRow::class => false);
    }
}
