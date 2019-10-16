<?php

namespace AcePedigree\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="pedigree_animal_kinship")
 */
class AnimalKinship
{
    /**
     * @var Animal
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Animal")
     * @ORM\JoinColumn(name="animal1Id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $animal1;

    /**
     * @var Animal
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Animal")
     * @ORM\JoinColumn(name="animal2Id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $animal2;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $covariance;

    private function __construct()
    { }

    /**
     * @return Animal
     */
    public function getAnimal1()
    {
        return $this->animal1;
    }

    /**
     * @return Animal
     */
    public function getAnimal2()
    {
        return $this->animal2;
    }

    /**
     * @return float
     */
    public function getCovariance()
    {
        return $this->covariance;
    }
}
