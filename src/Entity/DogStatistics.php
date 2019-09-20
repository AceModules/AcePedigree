<?php

namespace AcePedigree\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="pedigree_dog_statistics")
 */
class DogStatistics
{
    /**
     * @var Dog
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Dog", inversedBy="statistics")
     * @ORM\JoinColumn(name="dogId", referencedColumnName="id")
     */
    protected $dog;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $inbreedingCoefficient;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $averageCovariance;

    private function __construct()
    { }

    /**
     * @return Dog
     */
    public function getDog()
    {
        return $this->dog;
    }

    /**
     * @return float
     */
    public function getInbreedingCoefficient()
    {
        return $this->inbreedingCoefficient;
    }

    /**
     * @return float
     */
    public function getAverageCovariance()
    {
        return $this->averageCovariance;
    }
}
