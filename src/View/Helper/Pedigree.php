<?php

namespace AcePedigree\View\Helper;

use AcePedigree\Entity\DTO\DogDTO;
use AcePedigree\Entity\Dog;
use Zend\View\Helper\AbstractHelper;

class Pedigree extends AbstractHelper
{
    /**
     * @var DogDTO
     */
    protected $dto;

    /**
     * @var int
     */
    protected $maxGen;

    /**
     * @param Dog $dog
     * @param int $maxGen
     * @return string
     */
    public function __invoke(Dog $dog = null, $maxGen = 3)
    {
        if ($dog !== null) {
            $this->dto = $dog->getDTO();
            $this->maxGen = $maxGen;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->view->partial('partial/pedigree-table', ['dog' => $this->dto->getEntity(), 'maxGen' => $this->maxGen]);
    }

    /**
     * @return string
     */
    public function analysis()
    {
        return $this->view->partial('partial/pedigree-analysis', ['dog' => $this->dto->getEntity(), 'maxGen' => $this->maxGen]);
    }
}
