<?php

namespace AcePedigree\Entity;

use AceDatagrid\Annotation as Grid;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation as Form;

/**
 * @ORM\Entity
 * @ORM\Table(name="pedigree_country")
 * @Form\Name("country")
 * @Form\Hydrator("Zend\Hydrator\ClassMethods")
 * @Grid\Title(singular="Country", plural="Countries")
 */
class Country
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
     * @ORM\OneToMany(targetEntity="Dog", mappedBy="birthCountry")
     * @Form\Exclude()
     */
    protected $dogsBorn;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Dog", mappedBy="homeCountry")
     * @Form\Exclude()
     */
    protected $dogsHome;

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
     * @Grid\Header(label="Country", sort={"name"}, default=true)
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
    public function getDogsBorn()
    {
        return $this->dogsBorn;
    }

    /**
     * @return int
     * @Grid\Header(label="Dogs Born", sort={"count(dogsBorn.id), count(dogsHome.id)"}, reverse=true)
     */
    public function getDogsBornCount()
    {
        return count($this->dogsBorn);
    }

    /**
     * @return ArrayCollection
     */
    public function getDogsHome()
    {
        return $this->dogsHome;
    }

    /**
     * @return int
     * @Grid\Header(label="Dogs Standing", sort={"count(dogsHome.id), count(dogsBorn.id)"}, reverse=true)
     */
    public function getDogsHomeCount()
    {
        return count($this->dogsHome);
    }
}
