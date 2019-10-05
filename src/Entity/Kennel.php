<?php

namespace AcePedigree\Entity;

use AceDatagrid\Annotation as Grid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Form\Annotation as Form;

/**
 * @ORM\Entity
 * @ORM\Table(name="pedigree_kennel")
 * @Form\Name("kennel")
 * @Form\Hydrator("Zend\Hydrator\ClassMethods")
 * @Grid\Title(singular="Kennel", plural="Kennels")
 */
class Kennel
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Form\Exclude()
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=80)
     * @Form\Required(true)
     * @Form\Type("Zend\Form\Element\Text")
     * @Form\Options({"label": "Name"})
     * @Form\Filter({"name": "StringTrim"})
     * @Form\Filter({"name": "StripTags"})
     * @Form\Validator({"name": "StringLength", "options": {"max": "80"}})
     * @Grid\Search()
     */
    protected $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Dog", mappedBy="kennel")
     * @Form\Exclude()
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
     * @Grid\Header(label="Kennel", sort={"name"}, default=true)
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
     * @return int
     * @Grid\Header(label="Dogs", sort={"count(dogs.id)"}, reverse=true)
     */
    public function getDogsCount()
    {
        return count($this->dogs);
    }
}
