<?php

namespace AcePedigree\Controller;

use AcePedigree\Entity;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;

class TestMatingController extends AbstractActionController
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

    /**
     * @return array
     */
    public function indexAction()
    {
        $repository = $this->entityManager->getRepository(Entity\Animal::class);

        $entity = new Entity\Animal();
        $entity->setName('Future Offspring');

        $maxGen = (int) $this->params()->fromQuery('maxGen', 3);
        $maxGen = min(9, max(2, $maxGen));

        $sireId = (int) $this->params()->fromQuery('sire');
        $sire = $sireId ? $repository->find($sireId) : null;

        if ($sire && $sire->getSex() == Entity\Animal::SEX_MALE) {
            $entity->setSire($sire);
            $repository->findByDescendant($sire, $maxGen - 1);
        }

        $damId = (int) $this->params()->fromQuery('dam');
        $dam = $damId ? $repository->find($damId) : null;

        if ($dam && $dam->getSex() == Entity\Animal::SEX_FEMALE) {
            $entity->setDam($dam);
            $repository->findByDescendant($dam, $maxGen - 1);
        }

        return [
            'entity' => $entity,
            'maxGen' => $maxGen,
        ];
    }
}
