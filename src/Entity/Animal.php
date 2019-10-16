<?php

namespace AcePedigree\Entity;

use AcePedigree\Entity\DTO\AnimalDTO;
use AceDatagrid\Annotation as Grid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Zend\Form\Annotation as Form;

/**
 * @ORM\Entity(repositoryClass="AcePedigree\Entity\Repository\AnimalRepository")
 * @ORM\Table(name="pedigree_animal")
 * @Gedmo\Loggable(logEntryClass="LogEntry")
 * @Grid\Title(singular="Animal", plural="Animals")
 */
class Animal
{
    const SEX_MALE = 1;
    const SEX_FEMALE = 2;

    use TimestampableEntity;

    /**
     * @var array
     *
     * @Form\Exclude()
     */
    protected $sexLabels = array(
        self::SEX_MALE => 'Male',
        self::SEX_FEMALE => 'Female',
    );

    /**
     * @var AnimalDTO
     *
     * @Form\Exclude()
     */
    protected $dto;

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
     * @ORM\Column(type="string", length=50)
     * @Gedmo\Versioned
     * @Form\Required(true)
     * @Form\Type("Zend\Form\Element\Text")
     * @Form\Options({"label": "Registered Name"})
     * @Form\Filter({"name": "StringTrim"})
     * @Form\Filter({"name": "StripTags"})
     * @Form\Validator({"name": "StringLength", "options": {"max": "50"}})
     * @Grid\Search()
     * @Grid\Suggest()
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Text")
     * @Form\Options({"label": "Call Name"})
     * @Form\Filter({"name": "StringTrim"})
     * @Form\Filter({"name": "StripTags"})
     * @Form\Filter({"name": "ToNull"})
     * @Form\Validator({"name": "StringLength", "options": {"max": "15"}})
     * @Grid\Search()
     */
    protected $callName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=80, nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Text")
     * @Form\Options({"label": "Registration"})
     * @Form\Filter({"name": "StringTrim"})
     * @Form\Filter({"name": "StripTags"})
     * @Form\Filter({"name": "ToNull"})
     * @Form\Validator({"name": "StringLength", "options": {"max": "80"}})
     * @Grid\Search()
     * @Grid\Suggest()
     */
    protected $registration;

    /**
     * @var House
     * 
     * @ORM\ManyToOne(targetEntity="House", inversedBy="animals")
     * @ORM\JoinColumn(name="houseId", referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("AceAdmin\Form\Element\ObjectLiveSearch")
     * @Form\Attributes({"data-ajax-url": "/admin/houses/suggest"})
     * @Form\Options({"label": "House", "empty_option": "Select a House",
     *     "find_method": {
     *         "name": "findBy",
     *         "params": {"criteria": {}, "orderBy": {"name": "ASC"}}
     *     }
     * })
     * @Form\Filter({"name": "ToNull"})
     * @Grid\Search(columnName="house.name")
     */
    protected $house;

    /**
     * @var Animal
     * 
     * @ORM\ManyToOne(targetEntity="Animal")
     * @ORM\JoinColumn(name="sireId", referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("AceAdmin\Form\Element\ObjectLiveSearch")
     * @Form\Attributes({"data-ajax-url": "/admin/animals/suggest?sex=1"})
     * @Form\Options({"label": "Sire", "empty_option": "Select a Animal",
     *     "find_method": {
     *         "name": "findBy",
     *         "params": {"criteria": {"sex": 1}, "orderBy": {"name": "ASC"}}
     *     }
     * })
     * @Form\Filter({"name": "ToNull"})
     */
    protected $sire;

    /**
     * @var Animal
     * 
     * @ORM\ManyToOne(targetEntity="Animal")
     * @ORM\JoinColumn(name="damId", referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("AceAdmin\Form\Element\ObjectLiveSearch")
     * @Form\Attributes({"data-ajax-url": "/admin/animals/suggest?sex=2"})
     * @Form\Options({"label": "Dam", "empty_option": "Select a Animal",
     *     "find_method": {
     *         "name": "findBy",
     *         "params": {"criteria": {"sex": 2}, "orderBy": {"name": "ASC"}}
     *     }
     * })
     * @Form\Filter({"name": "ToNull"})
     */
    protected $dam;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     * @Form\Exclude()
     */
    protected $inbreedingCoefficient;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     * @Form\Exclude()
     */
    protected $averageCovariance;

    /**
     * @var int
     * 
     * @ORM\Column(type="integer")
     * @Gedmo\Versioned
     * @Form\Required(true)
     * @Form\Type("Zend\Form\Element\Radio")
     * @Form\Options({"label": "Sex"})
     * @Form\Attributes({"options": {
     *      "1": "Male",
     *      "2": "Female"
     * }})
     */
    protected $sex;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Person", inversedBy="animalsBred")
     * @ORM\JoinTable(name="pedigree_animal_breeder",
     *     joinColumns={@ORM\JoinColumn(name="animalId", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="breederId", referencedColumnName="id")}
     * )
     * @Form\Required(false)
     * @Form\Type("AceAdmin\Form\Element\ObjectLiveSearch")
     * @Form\Attributes({"data-ajax-url": "/admin/persons/suggest"})
     * @Form\Options({"label": "Breeders", "empty_option": "Select Breeders",
     *     "find_method": {
     *         "name": "findBy",
     *         "params": {"criteria": {}, "orderBy": {"name": "ASC"}}
     *     }
     * })
     * @Form\Attributes({"multiple": true})
     * @Form\Filter({"name": "ToNull"})
     * @Grid\Search(columnName="breeders.name")
     */
    protected $breeders;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Person", inversedBy="animalsOwned")
     * @ORM\JoinTable(name="pedigree_animal_owner",
     *     joinColumns={@ORM\JoinColumn(name="animalId", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="ownerId", referencedColumnName="id")}
     * )
     * @Form\Required(false)
     * @Form\Type("AceAdmin\Form\Element\ObjectLiveSearch")
     * @Form\Attributes({"data-ajax-url": "/admin/persons/suggest"})
     * @Form\Options({"label": "Owners", "empty_option": "Select Owners",
     *     "find_method": {
     *         "name": "findBy",
     *         "params": {"criteria": {}, "orderBy": {"name": "ASC"}}
     *     }
     * })
     * @Form\Attributes({"multiple": true})
     * @Form\Filter({"name": "ToNull"})
     * @Grid\Search(columnName="owners.name")
     */
    protected $owners;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Number")
     * @Form\Options({"label": "Birth Year"})
     * @Form\Filter({"name": "ToNull"})
     * @Form\Validator({"name": "Between", "options": {"min": "1900", "max": "2100"}})
     * @Grid\Search()
     */
    protected $birthYear;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Select")
     * @Form\Options({"label": "Birth Month"})
     * @Form\Attributes({"options": {
     *      "": "",
     *      "1": "January",
     *      "2": "February",
     *      "3": "March",
     *      "4": "April",
     *      "5": "May",
     *      "6": "June",
     *      "7": "July",
     *      "8": "August",
     *      "9": "September",
     *      "10": "October",
     *      "11": "November",
     *      "12": "December",
     * }})
     * @Form\Filter({"name": "ToNull"})
     */
    protected $birthMonth;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Number")
     * @Form\Options({"label": "Birth Day"})
     * @Form\Filter({"name": "ToNull"})
     * @Form\Validator({"name": "Between", "options": {"min": "1", "max": "31"}})
     */
    protected $birthDay;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Number")
     * @Form\Options({"label": "Death Year"})
     * @Form\Filter({"name": "ToNull"})
     * @Form\Validator({"name": "Between", "options": {"min": "1900", "max": "2100"}})
     */
    protected $deathYear;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Select")
     * @Form\Options({"label": "Death Month"})
     * @Form\Attributes({"options": {
     *      "": "",
     *      "1": "January",
     *      "2": "February",
     *      "3": "March",
     *      "4": "April",
     *      "5": "May",
     *      "6": "June",
     *      "7": "July",
     *      "8": "August",
     *      "9": "September",
     *      "10": "October",
     *      "11": "November",
     *      "12": "December",
     * }})
     * @Form\Filter({"name": "ToNull"})
     */
    protected $deathMonth;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Number")
     * @Form\Options({"label": "Death Day"})
     * @Form\Filter({"name": "ToNull"})
     * @Form\Validator({"name": "Between", "options": {"min": "1", "max": "31"}})
     */
    protected $deathDay;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="birthCountryId", referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("AceAdmin\Form\Element\ObjectLiveSearch")
     * @Form\Attributes({"data-ajax-url": "/admin/countries/suggest"})
     * @Form\Options({"label": "Land of Birth", "empty_option": "Select a Country",
     *     "find_method": {
     *         "name": "findBy",
     *         "params": {"criteria": {}, "orderBy": {"name": "ASC"}}
     *     }
     * })
     * @Form\Filter({"name": "ToNull"})
     * @Grid\Search(columnName="birthCountry.name")
     */
    protected $birthCountry;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="homeCountryId", referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("AceAdmin\Form\Element\ObjectLiveSearch")
     * @Form\Attributes({"data-ajax-url": "/admin/countries/suggest"})
     * @Form\Options({"label": "Land of Standing", "empty_option": "Select a Country",
     *     "find_method": {
     *         "name": "findBy",
     *         "params": {"criteria": {}, "orderBy": {"name": "ASC"}}
     *     }
     * })
     * @Form\Filter({"name": "ToNull"})
     * @Grid\Search(columnName="homeCountry.name")
     */
    protected $homeCountry;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Number")
     * @Form\Options({"label": "Height (Inches)"})
     * @Form\Filter({"name": "ToNull"})
     * @Form\Validator({"name": "Between", "options": {"min": "4", "max": "30"}})
     */
    protected $height;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Number")
     * @Form\Options({"label": "Weight (Pounds)"})
     * @Form\Filter({"name": "ToNull"})
     * @Form\Validator({"name": "Between", "options": {"min": "4", "max": "200"}})
     */
    protected $weight;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Select")
     * @Form\Options({"label": "Color"})
     * @Form\Attributes({"options": {
     *      "": "",
     *      "Red": "Red",
     *      "Brindle": "Brindle",
     *      "White": "White",
     *      "Other": "Other",
     * }})
     * @Form\Filter({"name": "ToNull"})
     * @Grid\Search()
     */
    protected $color;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Number")
     * @Form\Options({"label": "OFA Number"})
     * @Form\Filter({"name": "ToNull"})
     * @Form\Validator({"name":"Regex", "options":{"pattern":"/^\d{7}$/"}})
     */
    protected $ofaNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Textarea")
     * @Form\Options({"label": "Features"})
     * @Form\Filter({"name": "ToNull"})
     * @Grid\Search()
     */
    protected $features;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Textarea")
     * @Form\Options({"label": "Titles"})
     * @Form\Filter({"name": "ToNull"})
     * @Grid\Search()
     */
    protected $titles;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Textarea")
     * @Form\Options({"label": "Notes"})
     * @Form\Filter({"name": "ToNull"})
     * @Grid\Search()
     */
    protected $notes;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Image", mappedBy="animal")
     * @Form\Exclude()
     */
    protected $images;

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
        $this->breeders = new ArrayCollection();
        $this->owners = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    /**
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (!method_exists($this->getDTO(), $method)) {
            throw new \BadMethodCallException('Call to undefined method ' . Animal::class . '::' . $method . '()');
        }

        return call_user_func_array([$this->getDTO(), $method], $args);
    }

    /**
     * @return AnimalDTO
     */
    public function getDTO()
    {
        if (!isset($this->dto)) {
            $this->dto = new AnimalDTO($this);
        }

        return $this->dto;
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
     */
    public function getCallName()
    {
        return $this->callName;
    }

    /**
     * @param string $callName
     */
    public function setCallName($callName)
    {
        $this->callName = $callName;
    }

    /**
     * @return string
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param string $registration
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }

    /**
     * @return House
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * @param House $house
     */
    public function setHouse(House $house = null)
    {
        $this->house = $house;
    }

    /**
     * @return Animal
     * @Grid\Header(label="Sire", sort={"sire.name", "name"})
     */
    public function getSire()
    {
        return $this->sire;
    }

    /**
     * @param Animal $sire
     */
    public function setSire(Animal $sire = null)
    {
        if ($sire && $sire->isDescendantOf($this->getDTO())) {
            throw new \Exception(sprintf('Animal \'%s\' cannot be sire of his ancestor \'%s\'', $sire, $this));
        }

        unset($this->dto);
        $this->sire = $sire;
    }

    /**
     * @return Animal
     * @Grid\Header(label="Dam", sort={"dam.name", "name"})
     */
    public function getDam()
    {
        return $this->dam;
    }

    /**
     * @param Animal $dam
     */
    public function setDam(Animal $dam = null)
    {
        if ($dam && $dam->isDescendantOf($this->getDTO())) {
            throw new \Exception(sprintf('Animal \'%s\' cannot be dam of her ancestor \'%s\'', $dam, $this));
        }

        unset($this->dto);
        $this->dam = $dam;
    }

    /**
     * @return float
     */
    public function getInbreedingCoefficient()
    {
        return $this->inbreedingCoefficient;
    }

    /**
     * @return string
     * @Grid\Header(label="COI", sort={"inbreedingCoefficient", "averageCovariance", "-name"}, reverse=true)
     */
    public function getInbreedingCoefficientDisplay()
    {
        return round(100 * $this->inbreedingCoefficient, 2) . '%';
    }

    /**
     * @return float
     */
    public function getAverageCovariance()
    {
        return $this->averageCovariance;
    }

    /**
     * @return string
     * @Grid\Header(label="MK", sort={"averageCovariance", "inbreedingCoefficient", "-name"}, reverse=true)
     */
    public function getAverageCovarianceDisplay()
    {
        return round(100 * $this->averageCovariance, 2) . '%';
    }

    /**
     * @return int
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @return string
     * @Grid\Header(label="Sex", sort={"sex", "name"}, reverse=true)
     */
    public function getSexDisplay()
    {
        if (isset($this->sexLabels[$this->sex])) {
            return $this->sexLabels[$this->sex];
        }
        return 'Unknown';
    }

    /**
     * @param int $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return ArrayCollection
     */
    public function getBreeders()
    {
        return $this->breeders;
    }

    /**
     * @param ArrayCollection $breeders
     */
    public function setBreeders($breeders)
    {
        $this->breeders = $breeders;
    }

    /**
     * @param Person $breeder
     * @return boolean
     */
    public function hasBreeder(Person $breeder)
    {
        $this->breeders->contains($breeder);
    }

    /**
     * @param ArrayCollection $breeders
     */
    public function addBreeders(ArrayCollection $breeders)
    {
        foreach ($breeders as $breeder) {
            $this->breeders->add($breeder);
        }
    }

    /**
     * @param ArrayCollection $breeders
     */
    public function removeBreeders(ArrayCollection $breeders)
    {
        foreach ($breeders as $breeder) {
            $this->breeders->removeElement($breeder);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getOwners()
    {
        return $this->owners;
    }

    /**
     * @param ArrayCollection $owners
     */
    public function setOwners($owners)
    {
        $this->owners = $owners;
    }

    /**
     * @param Person $owner
     * @return boolean
     */
    public function hasOwner(Person $owner)
    {
        $this->owners->contains($owner);
    }

    /**
     * @param ArrayCollection $owners
     */
    public function addOwners(ArrayCollection $owners)
    {
        foreach ($owners as $owner) {
            $this->owners->add($owner);
        }
    }

    /**
     * @param ArrayCollection $owners
     */
    public function removeOwners(ArrayCollection $owners)
    {
        foreach ($owners as $owner) {
            $this->owners->removeElement($owner);
        }
    }

    /**
     * @return int
     * @Grid\Header(label="Birth Year", sort={"birthYear", "name"})
     */
    public function getBirthYear()
    {
        return $this->birthYear;
    }

    /**
     * @param int $birthYear
     */
    public function setBirthYear($birthYear)
    {
        $this->birthYear = $birthYear;
    }

    /**
     * @return int
     */
    public function getBirthMonth()
    {
        return $this->birthMonth;
    }

    /**
     * @param int $birthMonth
     */
    public function setBirthMonth($birthMonth)
    {
        $this->birthMonth = $birthMonth;
    }

    /**
     * @return int
     */
    public function getBirthDay()
    {
        return $this->birthDay;
    }

    /**
     * @param int $birthDay
     */
    public function setBirthDay($birthDay)
    {
        $this->birthDay = $birthDay;
    }

    /**
     * @return int
     */
    public function getDeathYear()
    {
        return $this->deathYear;
    }

    /**
     * @param int $deathYear
     */
    public function setDeathYear($deathYear)
    {
        $this->deathYear = $deathYear;
    }

    /**
     * @return int
     */
    public function getDeathMonth()
    {
        return $this->deathMonth;
    }

    /**
     * @param int $deathMonth
     */
    public function setDeathMonth($deathMonth)
    {
        $this->deathMonth = $deathMonth;
    }

    /**
     * @return int
     */
    public function getDeathDay()
    {
        return $this->deathDay;
    }

    /**
     * @param int $deathDay
     */
    public function setDeathDay($deathDay)
    {
        $this->deathDay = $deathDay;
    }

    /**
     * @return Country
     */
    public function getBirthCountry()
    {
        return $this->birthCountry;
    }

    /**
     * @param Country $birthCountry
     */
    public function setBirthCountry(Country $birthCountry = null)
    {
        $this->birthCountry = $birthCountry;
    }

    /**
     * @return Country
     */
    public function getHomeCountry()
    {
        return $this->homeCountry;
    }

    /**
     * @param Country $homeCountry
     */
    public function setHomeCountry(Country $homeCountry = null)
    {
        $this->homeCountry = $homeCountry;
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return int
     */
    public function getOfaNumber()
    {
        return $this->ofaNumber;
    }

    /**
     * @param int $ofaNumber
     */
    public function setOfaNumber($ofaNumber)
    {
        $this->ofaNumber = $ofaNumber;
    }

    /**
     * @return string
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * @param string $features
     */
    public function setFeatures($features)
    {
        $this->features = $features;
    }

    /**
     * @return string
     */
    public function getTitles()
    {
        return $this->titles;
    }

    /**
     * @param string $titles
     */
    public function setTitles($titles)
    {
        $this->titles = $titles;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }
}
