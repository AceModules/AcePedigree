<?php

namespace AcePedigree\Controller;

use AcePedigree\Entity\Person;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

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

    /**
     * @return array
     */
    public function indexAction()
    {
        return [
            'persons' => $this->entityManager->getRepository(Person::class)->findAll(),
        ];
    }

    /**
     * @return array
     */
    public function searchAction()
    {
        return [];
    }

    /**
     * @return JsonModel
     */
    public function suggestAction()
    {
        return new JsonModel();
    }

    /**
     * @return array
     */
    public function viewAction()
    {
        return [];
    }
}
