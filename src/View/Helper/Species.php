<?php

namespace AcePedigree\View\Helper;

use AcePedigree\Entity\Animal;
use AceDatagrid\Datagrid;
use AceDatagrid\DatagridManager;
use Doctrine\ORM\EntityManager;
use Zend\View\Helper\AbstractHelper;

class Species extends AbstractHelper
{
    /**
     * @var Datagrid
     */
    private $datagrid;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $datagridManager = new DatagridManager($entityManager);
        $this->datagrid = $datagridManager->get(Animal::class);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->datagrid->getSingularName();
    }

    /**
     * @return string
     */
    public function plural()
    {
        return $this->datagrid->getPluralName();
    }
}
