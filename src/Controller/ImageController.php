<?php

namespace AcePedigree\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;

class ImageController extends AbstractActionController
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
    public function uploadAction()
    {
        return [];
    }

    /**
     * @return array
     */
    public function viewAction()
    {
        return [];
    }
}
