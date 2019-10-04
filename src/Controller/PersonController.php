<?php

namespace AcePedigree\Controller;

use AcePedigree\Entity\Person;
use AcePedigree\Form\PersonSearch;
use AceDatagrid\DatagridManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\JsonModel;

class PersonController extends AbstractActionController
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
        $datagrid = $this->datagridManager->get(Person::class);

        $page = (int) $this->params()->fromQuery('page', 1);
        $sort = $this->params()->fromQuery('sort');
        $search = [];

        $form = new PersonSearch();
        $form->setData($this->getRequest()->getQuery());

        if ($form->isValid()) {
            $search = $form->getData();
            $queryBuilder = $this->entityManager->getRepository(Person::class)
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
            'form' => new PersonSearch(),
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
        $repository = $this->entityManager->getRepository(Person::class);

        $id = (int) $this->params()->fromRoute('id');
        $entity = $repository->find($id);

        if (!$entity) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return [
            'entity' => $entity,
        ];
    }
}
