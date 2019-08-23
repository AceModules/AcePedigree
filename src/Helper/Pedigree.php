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
        return $this->view->partial('partial/pedigree-table', ['dog' => $this->analysis->getDog(), 'maxGen' => $this->analysis->getMaxGen()]);
    }

    /**
     * @return string
     */
    public function analysis()
    {
        return $this->view->partial('partial/pedigree-analysis', ['maxGen' => $this->analysis->getMaxGen()]);
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
}
