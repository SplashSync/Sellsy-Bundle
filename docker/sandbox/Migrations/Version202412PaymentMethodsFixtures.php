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

use App\Entity\PaymentMethod;
use App\Entity\Taxe;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Populate Default Payment Methods IDs
 */
final class Version202412PaymentMethodsFixtures extends AbstractMigration implements ContainerAwareInterface
{
    private ContainerInterface $container;
    public function getDescription(): string
    {
        return 'Sellsy Sandbox: Populate Default Payment Methods IDs';
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

        foreach ($this->getMethods() as $taxe) {
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
     * @return PaymentMethod[]
     */
    private function getMethods(): array
    {
        $method1 = new PaymentMethod();
        $method1->label = "JohnnyCash";

        $method2 = new PaymentMethod();
        $method2->label = "WalkerBank";

        $method3 = new PaymentMethod();
        $method3->label = "DrunkFood";

        return array($method1, $method2, $method3);
    }
}
