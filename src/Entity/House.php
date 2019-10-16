<?php

namespace AcePedigree\Entity;

use AceDatagrid\Annotation as Grid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Zend\Form\Annotation as Form;

/**
 * @ORM\Entity
 * @ORM\Table(name="pedigree_house")
 * @Gedmo\Loggable(logEntryClass="LogEntry")
 * @Form\Name("house")
 * @Form\Hydrator("Zend\Hydrator\ClassMethods")
 * @Grid\Title(singular=PEDIGREE_HOUSE_SINGULAR, plural=PEDIGREE_HOUSE_PLURAL)
 */
class House
{
    use TimestampableEntity;

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
     * @Form\Type("Zend\Form\Element\Text")
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
     * @ORM\OneToMany(targetEntity="Animal", mappedBy="house")
     * @Form\Exclude()
     */
    protected $animals;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Form\Exclude()
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     * @Form\Exclude()
     */
    protected $updatedAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->animals = new ArrayCollection();
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
     * @Grid\Header(label="House", sort={"name"}, default=true)
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
    public function getAnimals()
    {
        return $this->animals;
    }

    /**
     * @return int
     * @Grid\Header(label="Animals", sort={"count(animals.id)"}, reverse=true)
     */
    public function getAnimalsCount()
    {
        return count($this->animals);
    }
}
