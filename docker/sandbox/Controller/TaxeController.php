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

namespace App\Controller;

use App\Entity\Taxe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour gérer les objets Taxe
 */
class TaxeController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @Route("/taxe/create", name="create_taxe", methods={"POST"})
     *
     * @throws \JsonException
     */
    public function createTaxe(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $rate = $data['rate'] ?? null;
        $label = $data['label'] ?? null;
        $isActive = $data['isActive'] ?? true;
        $isEcotax = $data['isEcotax'] ?? false;

        if (null === $rate || null === $label) {
            return new JsonResponse(array('error' => 'Missing data'), 400);
        }

        $taxe = new Taxe();
        $taxe->rate = $rate;
        $taxe->label = $label;
        $taxe->isActive = $isActive;
        $taxe->isEcotax = $isEcotax;

        $this->entityManager->persist($taxe);
        $this->entityManager->flush();

        return new JsonResponse(array('message' => 'Taxe créée avec succès', 'taxe' => $taxe), 201);
    }

    /**
     * @Route("/taxe/{id}", name="get_taxe", methods={"GET"})
     */
    public function getTaxe(int $id): JsonResponse
    {
        $taxe = $this->entityManager->getRepository(Taxe::class)->find($id);

        if (!$taxe) {
            throw new NotFoundHttpException('No taxe found for id '.$id);
        }

        return new JsonResponse(array('taxe' => $taxe));
    }

    /**
     * @Route("/taxe/{id}", name="update_taxe", methods={"PUT"})
     *
     * @throws \JsonException
     */
    public function updateTaxe(Request $request, int $id): JsonResponse
    {
        $taxe = $this->entityManager->getRepository(Taxe::class)->find($id);

        if (!$taxe) {
            throw new NotFoundHttpException('No taxe found for id '.$id);
        }

        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $rate = $data['rate'] ?? $taxe->rate;
        $label = $data['label'] ?? $taxe->label;
        $isActive = $data['isActive'] ?? $taxe->isActive;
        $isEcotax = $data['isEcotax'] ?? $taxe->isEcotax;

        $taxe->rate = $rate;
        $taxe->label = $label;
        $taxe->isActive = $isActive;
        $taxe->isEcotax = $isEcotax;

        $this->entityManager->flush();

        return new JsonResponse(array('message' => 'Taxe mise à jour avec succès', 'taxe' => $taxe));
    }

    /**
     * @Route("/taxe/{id}", name="delete_taxe", methods={"DELETE"})
     */
    public function deleteTaxe(int $id): JsonResponse
    {
        $taxe = $this->entityManager->getRepository(Taxe::class)->find($id);

        if (!$taxe) {
            throw new NotFoundHttpException('No taxe found for id '.$id);
        }

        $this->entityManager->remove($taxe);
        $this->entityManager->flush();

        return new JsonResponse(array('message' => 'Taxe supprimée avec succès'));
    }
}
