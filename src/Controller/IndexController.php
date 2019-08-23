<?php

namespace AcePedigree\Controller;

use AcePedigree\Entity;
use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;

class IndexController extends AbstractActionController
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Landing page
    public function indexAction()
    {
        return [];
    }

    // Recent database updates
    public function recentAction()
    {
        return [];
    }

    // Statistic data about dogs
    public function statisticsAction()
    {
        return [];
    }

    // Test mating pedigree
    public function testMatingAction()
    {
        $repository = $this->entityManager->getRepository(Entity\Dog::class);

        $entity = new Entity\Dog();
        $entity->setName('Future Offspring');

        $maxGen = (int) $this->params()->fromQuery('gens', 3);
        $maxGen = min(9, max(3, $maxGen));

        $sireId = (int) $this->params()->fromQuery('sire');
        $sire = $repository->find($sireId);

        if ($sire && $sire->getSex() == Entity\Dog::SEX_MALE) {
            $entity->setSire($sire);
            $repository->findByDescendant($sire, $maxGen - 1);
        }

        $damId = (int) $this->params()->fromQuery('dam');
        $dam = $repository->find($damId);

        if ($dam && $dam->getSex() == Entity\Dog::SEX_FEMALE) {
            $entity->setDam($dam);
            $repository->findByDescendant($dam, $maxGen - 1);
        }

        return [
            'entity' => $entity,
            'maxGen' => $maxGen,
        ];
    }
}
