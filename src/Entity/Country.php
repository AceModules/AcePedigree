<?php

namespace AcePedigree\Entity;

use AceDatagrid\Annotation as Grid;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Laminas\Form\Annotation as Form;

/**
 * @ORM\Entity
 * @ORM\Table(name="pedigree_country")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 * @Gedmo\Loggable(logEntryClass="LogEntry")
 * @Form\Name("country")
 * @Form\Hydrator("Laminas\Hydrator\ClassMethodsHydrator")
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
     * @Gedmo\Versioned
     * @Form\Required(true)
     * @Form\Type("Laminas\Form\Element\Text")
     * @Form\Options({"label": "Name"})
     * @Form\Filter({"name": "StringTrim"})
     * @Form\Filter({"name": "StripTags"})
     * @Form\Validator({"name": "StringLength", "options": {"max": "80"}})
     * @Grid\Search()
     * @Grid\Suggest()
     */
    protected $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Animal", mappedBy="birthCountry")
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Form\Exclude()
     */
    protected $animalsBorn;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Animal", mappedBy="homeCountry")
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Form\Exclude()
     */
    protected $animalsHome;

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
    public function getAnimalsBorn()
    {
        return $this->animalsBorn;
    }

    /**
     * @return int
     * @Grid\Header(label="# Born", sort={"count(animalsBorn.id), count(animalsHome.id)"}, reverse=true)
     */
    public function getAnimalsBornCount()
    {
        return count($this->animalsBorn);
    }

    /**
     * @return ArrayCollection
     */
    public function getAnimalsHome()
    {
        return $this->animalsHome;
    }

    /**
     * @return int
     * @Grid\Header(label="# Residing", sort={"count(animalsHome.id), count(animalsBorn.id)"}, reverse=true)
     */
    public function getAnimalsHomeCount()
    {
        return count($this->animalsHome);
    }
}
