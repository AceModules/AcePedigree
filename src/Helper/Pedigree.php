<?php

namespace AcePedigree\Helper;

use AcePedigree\Entity\Dog;
use AcePedigree\Service\PedigreeAnalysis;
use Zend\View\Helper\AbstractHelper;

class Pedigree extends AbstractHelper
{
    /**
     * @var PedigreeAnalysis
     */
    protected $analysis;

    /**
     * @param Dog $dog
     * @param int $maxGen
     * @return string
     */
    public function __invoke(Dog $dog = null, $maxGen = 3)
    {
        if ($dog !== null) {
            $this->analysis = new PedigreeAnalysis($dog, $maxGen);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $html = '<table class="pedigree max-gen-' . $this->analysis->getMaxGen() . '"><tr>' .
            $this->pedigreeCell($this->analysis->getDog(), $this->analysis->getMaxGen()) .
            '</tr></table>';

        return str_replace('<tr></tr>', '', $html);
    }

    /**
     * @return string
     */
    public function completeGens()
    {
        return $this->analysis->getCompleteGens();
    }

    /**
     * @return string
     */
    public function coefficientOfInbreeding()
    {
        return $this->analysis->getCoefficientOfInbreeding();
    }

    /**
     * @return string
     */
    public function relationshipCoefficient()
    {
        return $this->analysis->getRelationshipCoefficient();
    }

    /**
     * @return string
     */
    public function ancestorLoss()
    {
        return $this->analysis->getAncestorLoss();
    }

    /**
     * @return array
     */
    public function ancestorsArray()
    {
        return $this->analysis->getAncestorsByBlood();
    }

    /**
     * @param Dog $dog
     * @param int $maxGen
     * @param int $gen
     * @return string
     */
    private function pedigreeCell(Dog $dog = null, $maxGen = 3, $gen = 0)
    {
        $html = '<td class="gen-' . $gen . '" rowspan="' . (1 << ($maxGen - $gen)) . '">' . $this->view->partial('partial/pedigree-cell.phtml', ['dog' => $dog]) . '</td>';

        if ($gen < $maxGen) {
            $html .= $this->pedigreeCell(($dog ? $dog->getSire() : null), $maxGen, $gen + 1);
            $html .= $this->pedigreeCell(($dog ? $dog->getDam() : null), $maxGen, $gen + 1);
        } else {
            $html .= '</tr><tr>';
        }

        return $html;
    }
}
