<?php

namespace AcePedigree\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="pedigree_animal_kinship")
 */
class DogKinship
{
    /**
     * @var Dog
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Dog")
     * @ORM\JoinColumn(name="dog1Id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $dog1;

    /**
     * @var Dog
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Dog")
     * @ORM\JoinColumn(name="dog2Id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $dog2;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $covariance;

    private function __construct()
    { }

    /**
     * @return Dog
     */
    public function getDog1()
    {
        return $this->dog1;
    }

    /**
     * @return Dog
     */
    public function getDog2()
    {
        return $this->dog2;
    }

    /**
     * @return float
     */
    public function getCovariance()
    {
        return $this->covariance;
    }
}
