<?php

namespace AcePedigree\Controller;

use AcePedigree\Entity;
use Doctrine\ORM\EntityManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

class StatisticsController extends AbstractActionController
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
    public function populationAction()
    {
        return [];
    }

    /**
     * @return JsonModel
     */
    public function populationDataAction()
    {
        $repository = $this->entityManager->getRepository(Entity\Animal::class);
        list($nodes, $links) = $repository->getForceDirectedKinshipData();

        return new JsonModel([
            'nodes' => $nodes,
            'links' => $links,
        ]);
    }
}
