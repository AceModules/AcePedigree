<?php

namespace AcePedigree\Helper;

use AcePedigree\Entity\Dog;
use Zend\View\Helper\AbstractHelper;

class PedigreeTable extends AbstractHelper
{
    /**
     * @param Dog $dog
     * @param int $maxGen
     * @return string
     */
    public function __invoke(Dog $dog = null, $maxGen = 3)
    {
        return str_replace('<tr></tr>', '', '<table class="pedigree max-gen-' . $maxGen .'"><tr>' . $this->getPedigreeCell($dog, $maxGen, 0) . '</tr></table>');
    }

    /**
     * @param Dog $dog
     * @param int $maxGen
     * @param int $gen
     * @return string
     */
    private function getPedigreeCell(Dog $dog = null, $maxGen = 3, $gen = 0)
    {
        $html = '<td class="gen-' . $gen . '" rowspan="' . (1 << ($maxGen - $gen)) . '">' . $this->view->partial('partial/pedigree-cell.phtml', ['dog' => $dog]) . '</td>';

        if ($gen < $maxGen) {
            $html .= $this->getPedigreeCell(($dog ? $dog->getSire() : null), $maxGen, $gen + 1);
            $html .= $this->getPedigreeCell(($dog ? $dog->getDam() : null), $maxGen, $gen + 1);
        } else {
            $html .= '</tr><tr>';
        }

        return $html;
    }
}
