<?php

namespace AcePedigree\View\Helper;

use AcePedigree\Entity\Animal;
use Zend\View\Helper\AbstractHelper;

class TestMatingButton extends AbstractHelper
{
    /**
     * @param Animal $animal
     * @return string
     */
    public function __invoke(Animal $animal)
    {
        if ($animal->getId() && ($animal->getSex() == Animal::SEX_MALE || $animal->getSex() == Animal::SEX_FEMALE)) {
            $param = $animal->getSex() == Animal::SEX_MALE ? 'sire' : 'dam';
            return '<a class="btn btn-primary" href="' .
                $this->view->url('ace-pedigree/test-mating', [], ['query' => [$param => $animal->getId()]]) .
                '" role="button"><span class="glyphicon glyphicon-random"></span>&nbsp; Test Mating</a>';
        }
    }
}
