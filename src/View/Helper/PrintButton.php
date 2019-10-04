<?php

namespace AcePedigree\View\Helper;

use AcePedigree\Entity\Dog;
use Zend\View\Helper\AbstractHelper;

class PrintButton extends AbstractHelper
{
    /**
     * @param Dog $dog
     * @return string
     */
    public function __invoke(Dog $dog)
    {
        return '<a class="btn btn-primary" href="' .
            $this->view->url('ace-pedigree/dogs/view', ['action' => 'print', 'id' => $dog->getId()]) .
            '" role="button"><span class="glyphicon glyphicon-print"></span>&nbsp; Print Friendly Pedigree</a>';
    }
}
