<?php

namespace AcePedigree\Controller;

use AcePedigree\Entity;
use AceDatagrid\DatagridManager;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var DatagridManager
     */
    private $datagridManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->datagridManager = new DatagridManager($entityManager);
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @return array
     */
    public function recentAction()
    {
        $datagrid = $this->datagridManager->get(Entity\Animal::class);

        $animals = $this->entityManager->getRepository(Entity\Animal::class)->findBy([], ['updatedAt' => 'DESC'], 10);
        $persons = $this->entityManager->getRepository(Entity\Person::class)->findBy([], ['updatedAt' => 'DESC'], 10);

        return [
            'singularAnimal' => $datagrid->getSingularName(),
            'pluralAnimals'  => $datagrid->getPluralName(),
            'animals'        => $animals,
            'persons'        => $persons,
        ];
    }
}
