<?php

namespace AcePedigree\Controller;

use AcePedigree\Entity;
use AceDatagrid\DatagridManager;
use AcePedigree\Form\AdvancedSearch;
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
        $datagrid = $this->datagridManager->get(Entity\Dog::class);

        $page = (int) $this->params()->fromQuery('page', 1);
        $sort = $this->params()->fromQuery('sort');

        $form = new AdvancedSearch();
        $form->setData($this->getRequest()->getQuery());

        if ($form->isValid()) {
            $queryBuilder = $this->entityManager->getRepository(Entity\Dog::class)
                ->createSearchQueryBuilder($datagrid, $form->getData(), $sort);
        } else {
            $queryBuilder = $datagrid->createSearchQueryBuilder(null, $sort);
        }

        $paginator = new Paginator(new DoctrineAdapter(new ORMPaginator($queryBuilder)));
        $paginator->setDefaultItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);

        return [
            'columns'  => $datagrid->getHeaderColumns(),
            'result'   => $paginator,
            'page'     => $page,
            'sort'     => $sort,
        ];
    }

    // Search dogs
    public function searchAction()
    {
        return [
            'form' => new AdvancedSearch(),
        ];
    }

    // Check for partial name match
    public function checkAction()
    {
        return [];
    }

    // View dog details
    public function viewAction()
    {
        $repository = $this->entityManager->getRepository(Entity\Dog::class);

        $id = (int) $this->params()->fromRoute('id');
        $entity = $repository->find($id);

        if (!$entity) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $maxGen = (int) $this->params()->fromQuery('gens', 3);
        $maxGen = min(9, max(3, $maxGen));

        $offspring = $repository->findByParent($entity);
        $siblings = $repository->findBySibling($entity);
        $ancestors = $repository->findByDescendant($entity, $maxGen);

        $fullSiblings = array_filter($siblings, function ($sibling) use ($entity) {
            return $sibling->getSire() == $entity->getSire() && $sibling->getDam() == $entity->getDam();
        });

        $sireHalfSiblings = array_filter($siblings, function ($sibling) use ($entity) {
            return $sibling->getSire() == $entity->getSire() && $sibling->getDam() != $entity->getDam();
        });

        $damHalfSiblings = array_filter($siblings, function ($sibling) use ($entity) {
            return $sibling->getSire() != $entity->getSire() && $sibling->getDam() == $entity->getDam();
        });

        return [
            'entity'           => $entity,
            'offspring'        => $offspring,
            'fullSiblings'     => $fullSiblings,
            'sireHalfSiblings' => $sireHalfSiblings,
            'damHalfSiblings'  => $damHalfSiblings,
            'ancestors'        => $ancestors,
            'maxGen'           => $maxGen,
        ];
    }

    // Print friendly pedigree
    public function printAction()
    {
        return [];
    }
}
