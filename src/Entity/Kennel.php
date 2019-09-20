<?php

namespace AcePedigree\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="pedigree_kennel")
 */
class Kennel
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=80)
     */
    protected $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Dog", mappedBy="kennel")
     */
    protected $dogs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dogs = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return ArrayCollection
     */
    public function getDogs()
    {
        return $this->dogs;
    }

    /**
     * @param Dog $dog
     * @return boolean
     */
    public function hasDog(Dog $dog)
    {
        $this->dogs->contains($dog);
    }

    /**
     * @param ArrayCollection $dogs
     */
    public function addDogs(ArrayCollection $dogs)
    {
        foreach ($dogs as $dog) {
            $this->dogs->add($dog);
        }
    }

    /**
     * @param ArrayCollection $dogs
     */
    public function removeDogs(ArrayCollection $dogs)
    {
        foreach ($dogs as $dog) {
            $this->dogs->removeElement($dog);
        }
    }
}
