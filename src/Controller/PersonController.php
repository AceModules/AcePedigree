<?php

namespace AcePedigree\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;

class PersonController extends AbstractActionController
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

    // Browse persons alphabetically
    public function indexAction()
    {
        return [
            'persons' => $this->entityManager->getRepository('AcePedigree\Entity\Person')->findAll(),
        ];
    }

    // Search persons
    public function searchAction()
    {
        return [];
    }

    // View person details
    public function viewAction()
    {
        return [];
    }
}
