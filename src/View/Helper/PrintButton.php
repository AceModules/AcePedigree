<?php

namespace AcePedigree\View\Helper;

use AcePedigree\Entity\Animal;
use Laminas\View\Helper\AbstractHelper;

class PrintButton extends AbstractHelper
{
    /**
     * @param Animal $animal
     * @return string
     */
    public function __invoke(Animal $animal)
    {
        return '<a class="btn btn-primary" href="' .
            $this->view->url('ace-pedigree/animals/view', ['action' => 'print', 'id' => $animal->getId()]) .
            '" role="button" target="_blank"><span class="fas fa-print"></span>&nbsp; Print Friendly Pedigree</a>';
    }
}
