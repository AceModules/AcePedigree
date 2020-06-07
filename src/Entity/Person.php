<?php

namespace AcePedigree\Entity;

use AceDatagrid\Annotation as Grid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Laminas\Form\Annotation as Form;

/**
 * @ORM\Entity(repositoryClass="AcePedigree\Entity\Repository\PersonRepository")
 * @ORM\Table(name="pedigree_person")
 * @Gedmo\Loggable(logEntryClass="LogEntry")
 * @Form\Name("person")
 * @Form\Hydrator("Laminas\Hydrator\ClassMethods")
 * @Grid\Title(singular="Person", plural="Persons")
 */
class Person
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
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Laminas\Form\Element\Email")
     * @Form\Options({"label": "Email"})
     * @Form\Filter({"name": "ToNull"})
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Laminas\Form\Element\Url")
     * @Form\Options({"label": "Website"})
     * @Form\Filter({"name": "ToNull"})
     */
    protected $website;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Laminas\Form\Element\Text")
     * @Form\Options({"label": "Street Address"})
     * @Form\Filter({"name": "StringTrim"})
     * @Form\Filter({"name": "StripTags"})
     * @Form\Filter({"name": "ToNull"})
     * @Form\Validator({"name": "StringLength", "options": {"max": "50"}})
     */
    protected $street;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Laminas\Form\Element\Text")
     * @Form\Options({"label": "City"})
     * @Form\Filter({"name": "StringTrim"})
     * @Form\Filter({"name": "StripTags"})
     * @Form\Filter({"name": "ToNull"})
     * @Form\Validator({"name": "StringLength", "options": {"max": "30"}})
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Laminas\Form\Element\Text")
     * @Form\Options({"label": "State/Region"})
     * @Form\Filter({"name": "StringTrim"})
     * @Form\Filter({"name": "StripTags"})
     * @Form\Filter({"name": "ToNull"})
     * @Form\Validator({"name": "StringLength", "options": {"max": "30"}})
     */
    protected $region;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Laminas\Form\Element\Text")
     * @Form\Options({"label": "Postal Code"})
     * @Form\Filter({"name": "StringTrim"})
     * @Form\Filter({"name": "StripTags"})
     * @Form\Filter({"name": "ToNull"})
     * @Form\Validator({"name": "StringLength", "options": {"max": "15"}})
     */
    protected $postalCode;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="countryId", referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("AceAdmin\Form\Element\ObjectLiveSearch")
     * @Form\Options({"label": "Country", "empty_option": "Select a Country",
     *     "find_method": {
     *         "name": "findBy",
     *         "params": {"criteria": {}, "orderBy": {"name": "ASC"}}
     *     },
     *     "ajax_route": {
     *         "name": "ace-admin/entity",
     *         "params": {"action": "suggest", "entity": "countries"}
     *     }
     * })
     * @Form\Filter({"name": "ToNull"})
     * @Grid\Search(columnName="country.name")
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("AceAdmin\Form\Element\Phone")
     * @Form\Options({"label": "Phone"})
     * @Form\Filter({"name": "ToNull"})
     */
    protected $phone;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Animal", mappedBy="breeders")
     * @Form\Exclude()
     */
    protected $animalsBred;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Animal", mappedBy="owners")
     * @Form\Exclude()
     */
    protected $animalsOwned;

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
        $this->animalsBred = new ArrayCollection();
        $this->animalsOwned = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return string
     * @Grid\Header(label="Name", sort={"name"}, default=true)
     */
    public function getSelf()
    {
        return $this;
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
     * @return string
     * @Grid\Header(label="Email", sort={"email"})
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     * @Grid\Header(label="Website", sort={"website"})
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return implode(', ', array_filter([$this->street, $this->city, $this->region, $this->postalCode]));
    }

    /**
     * @return Country
     * @Grid\Header(label="Country", sort={"country.name"})
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return ArrayCollection
     */
    public function getAnimalsBred()
    {
        return $this->animalsBred;
    }

    /**
     * @return int
     * @Grid\Header(label="# Bred", sort={"count(animalsBred.id)"}, reverse=true)
     */
    public function getAnimalsBredCount()
    {
        return count($this->animalsBred);
    }

    /**
     * @return ArrayCollection
     */
    public function getAnimalsOwned()
    {
        return $this->animalsOwned;
    }

    /**
     * @return int
     * @Grid\Header(label="# Owned", sort={"count(animalsOwned.id)"}, reverse=true)
     */
    public function getAnimalsOwnedCount()
    {
        return count($this->animalsOwned);
    }
}
