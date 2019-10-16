<?php

namespace AcePedigree\Controller;

use AcePedigree\Entity\Animal;
use AcePedigree\Form\AdvancedSearch;
use AceDatagrid\DatagridManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AnimalController extends AbstractActionController
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

    /**
     * @return array
     */
    public function indexAction()
    {
        $datagrid = $this->datagridManager->get(Animal::class);

        $page = (int) $this->params()->fromQuery('page', 1);
        $sort = $this->params()->fromQuery('sort');
        $search = [];

        $form = new AdvancedSearch();
        $form->setData($this->getRequest()->getQuery());

        if ($form->isValid()) {
            $search = $form->getData();
            $queryBuilder = $this->entityManager->getRepository(Animal::class)
                ->createSearchQueryBuilder($datagrid, $search, $sort);
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
            'search'   => $search,
        ];
    }

    /**
     * @return array
     */
    public function searchAction()
    {
        return [
            'form' => new AdvancedSearch(),
        ];
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
        $repository = $this->entityManager->getRepository(Animal::class);

        $id = (int) $this->params()->fromRoute('id');
        $entity = $repository->find($id);

        if (!$entity) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $maxGen = (int) $this->params()->fromQuery('maxGen', 3);
        $maxGen = min(9, max(2, $maxGen));

        $offspring = $repository->findByParent($entity);
        $siblings = $repository->findBySibling($entity);
        $ancestors = $repository->findByDescendant($entity);

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

    /**
     * @return ViewModel
     */
    public function printAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);

        $repository = $this->entityManager->getRepository(Animal::class);

        $id = (int) $this->params()->fromRoute('id');
        $entity = $repository->find($id);

        if (!$entity) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $maxGen = (int) $this->params()->fromQuery('maxGen', 3);
        $maxGen = min(9, max(2, $maxGen));

        $ancestors = $repository->findByDescendant($entity);

        return $viewModel->setVariables([
            'entity'    => $entity,
            'ancestors' => $ancestors,
            'maxGen'    => $maxGen,
        ]);
    }
}
