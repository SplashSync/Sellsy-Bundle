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

namespace DoctrineMigrations;

// phpcs:disable Generic.Files.LineLength

use App\Entity\Taxe;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Populate Default FR Tax IDs
 */
final class Version202407TaxeFixtures extends AbstractMigration implements ContainerAwareInterface
{
    private ContainerInterface $container;
    public function getDescription(): string
    {
        return 'Sellsy Sandbox: Populate Default FR Tax IDs';
    }

    /**
     * Inject Container
     */
    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function up(Schema $schema): void
    {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function postUp(Schema $schema): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        foreach ($this->getTaxes() as $taxe) {
            if (!$em->contains($taxe)) {
                $em->persist($taxe);
            }
        }

        $em->flush();
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function down(Schema $schema): void
    {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function postDown(Schema $schema): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        foreach ($em->getRepository(Taxe::class)->findAll() as $taxe) {
            $em->remove($taxe);
        }

        $em->flush();
    }

    /**
     * @return Taxe[]
     */
    private function getTaxes(): array
    {
        $taxes = array();

        $vatFr20 = new Taxe();
        $vatFr20->rate = 20.0;
        $vatFr20->label = "VATFR20";

        $vatFr10 = new Taxe();
        $vatFr10->rate = 10.0;
        $vatFr10->label = "VATFR10";

        $vatFr55 = new Taxe();
        $vatFr55->rate = 5.5;
        $vatFr55->label = "VATFR55";

        $vatFr0 = new Taxe();
        $vatFr0->rate = 0.0;
        $vatFr0->label = "VATFR0";

        return array($vatFr20, $vatFr10, $vatFr55, $vatFr0, $vatFr55);
    }
}
