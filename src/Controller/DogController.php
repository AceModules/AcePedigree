<?php

namespace AcePedigree\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;

class DogController extends AbstractActionController
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

    // Browse dogs alphabetically
    public function indexAction()
    {
        return [
            'dogs' => $this->entityManager->getRepository('AcePedigree\Entity\Dog')->findAll(),
        ];
    }

    // Search dogs
    public function searchAction()
    {
        return [];
    }

    // Check for partial name match
    public function checkAction()
    {
        return [];
    }

    // View dog details
    public function viewAction()
    {
        return [];
    }

    // Print friendly pedigree
    public function printAction()
    {
        return [];
    }
}
