<?php

namespace AcePedigree\Controller;

use Zend\Mvc\Controller\AbstractActionController;
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

    // Upload an image file
    public function uploadAction()
    {
        return [];
    }

    // View an uploaded image file
    public function viewAction()
    {
        return [];
    }
}
