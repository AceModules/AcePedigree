<?php

namespace AcePedigree\View\Helper;

use AcePedigree\Entity\DTO\AnimalDTO;
use AcePedigree\Entity\Animal;
use Zend\View\Helper\AbstractHelper;

class Pedigree extends AbstractHelper
{
    /**
     * @var AnimalDTO
     */
    protected $dto;

    /**
     * @var int
     */
    protected $maxGen;

    /**
     * @param Animal $animal
     * @param int $maxGen
     * @return string
     */
    public function __invoke(Animal $animal = null, $maxGen = 3)
    {
        if ($animal !== null) {
            $this->dto = $animal->getDTO();
            $this->maxGen = $maxGen;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->view->partial('partial/pedigree-table', ['animal' => $this->dto->getEntity(), 'maxGen' => $this->maxGen]);
    }

    /**
     * @return string
     */
    public function analysis()
    {
        return $this->view->partial('partial/pedigree-analysis', ['animal' => $this->dto->getEntity(), 'maxGen' => $this->maxGen]);
    }
}
