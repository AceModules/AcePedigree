<?php

namespace AcePedigree\Controller;

use AceDatagrid\DatagridManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;

class DogController extends AbstractActionController
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

    // Browse dogs alphabetically
    public function indexAction()
    {
        $datagrid = $this->datagridManager->get('AcePedigree\Entity\Dog');

        $search = $this->params()->fromQuery('q');
        $page = (int)$this->params()->fromQuery('page', 1);
        $sort = $this->params()->fromQuery('sort');

        $queryBuilder = $datagrid->createSearchQueryBuilder($search, $sort);
        $paginator = new Paginator(new DoctrineAdapter(new ORMPaginator($queryBuilder)));
        $paginator->setDefaultItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);

        return [
            'singular' => $datagrid->getSingularName(),
            'plural' => $datagrid->getPluralName(),
            'columns' => $datagrid->getHeaderColumns(),
            'result' => $paginator,
            'search' => $search,
            'page' => $page,
            'sort' => $sort,
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
