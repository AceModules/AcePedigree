<?php

namespace AcePedigree\Helper;

use AcePedigree\Entity\Dog;
use Zend\View\Helper\AbstractHelper;

class PedigreeTable extends AbstractHelper
{
    /**
     * @param Dog $dog
     * @return string
     */
    public function __invoke(Dog $dog = null, $maxGen = 3)
    {
        return str_replace('<tr></tr>', '', '<table class="pedigree pedigree-gen-' . $maxGen .'"><tr>' . $this->pedigreeCell($dog, $maxGen, 0) . '</tr></table>');
    }

    /**
     * @param Dog $dog
     * @return string
     */
    private function pedigreeCell(Dog $dog = null, $maxGen = 3, $gen = 0)
    {
        $html = '<td rowspan="' . (1 << ($maxGen - $gen)) . '">' . $this->view->partial('partial/pedigree-cell.phtml', ['dog' => $dog]) . '</td>';

        if ($gen < $maxGen) {
            $html .= $this->pedigreeCell(($dog ? $dog->getSire() : null), $maxGen, $gen + 1);
            $html .= $this->pedigreeCell(($dog ? $dog->getDam() : null), $maxGen, $gen + 1);
        } else {
            $html .= '</tr><tr>';
        }

        return $html;
    }
}
