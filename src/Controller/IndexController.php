<?php

namespace AcePedigree\Controller;

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
        return [];
    }
}
