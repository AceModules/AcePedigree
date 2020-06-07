<?php

namespace AcePedigree\Controller;

use AcePedigree\Entity;
use Doctrine\ORM\EntityManager;
use Laminas\Mvc\Controller\AbstractActionController;

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
        $animals = $this->entityManager->getRepository(Entity\Animal::class)->findBy([], ['updatedAt' => 'DESC'], 10);
        $persons = $this->entityManager->getRepository(Entity\Person::class)->findBy([], ['updatedAt' => 'DESC'], 10);

        return [
            'animals' => $animals,
            'persons' => $persons,
        ];
    }
}
