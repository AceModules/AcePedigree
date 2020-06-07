<?php

namespace AcePedigree\Controller;

use AcePedigree\Entity;
use AcePedigree\Form\AnimalSuggest;
use Doctrine\ORM\EntityManager;
use Laminas\Mvc\Controller\AbstractActionController;

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

        $form = new AnimalSuggest();

        $sireId = (int) $this->params()->fromQuery('sire');
        $sire = $sireId ? $repository->find($sireId) : null;

        if ($sire && $sire->getSex() == Entity\Animal::SEX_MALE) {
            $entity->setSire($sire);
            $repository->findByDescendant($sire, $maxGen - 1);
            $form->get('sire')->setValue($sireId);
        }

        $damId = (int) $this->params()->fromQuery('dam');
        $dam = $damId ? $repository->find($damId) : null;

        if ($dam && $dam->getSex() == Entity\Animal::SEX_FEMALE) {
            $entity->setDam($dam);
            $repository->findByDescendant($dam, $maxGen - 1);
            $form->get('dam')->setValue($damId);
        }


        return [
            'entity' => $entity,
            'maxGen' => $maxGen,
            'form'   => $form,
        ];
    }
}
