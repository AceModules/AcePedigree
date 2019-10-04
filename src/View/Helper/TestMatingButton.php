<?php

namespace AcePedigree\View\Helper;

use AcePedigree\Entity\Dog;
use Zend\View\Helper\AbstractHelper;

class TestMatingButton extends AbstractHelper
{
    /**
     * @param Dog $dog
     * @return string
     */
    public function __invoke(Dog $dog)
    {
        if ($dog->getId() && ($dog->getSex() == Dog::SEX_MALE || $dog->getSex() == Dog::SEX_FEMALE)) {
            $param = $dog->getSex() == Dog::SEX_MALE ? 'sire' : 'dam';
            return '<a class="btn btn-primary" href="' .
                $this->view->url('ace-pedigree/test-mating', [], ['query' => [$param => $dog->getId()]]) .
                '" role="button"><span class="glyphicon glyphicon-random"></span>&nbsp; Test Mating</a>';
        }
    }
}
