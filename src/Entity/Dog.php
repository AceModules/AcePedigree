<?php

namespace AcePedigree\Entity;

use AcePedigree\DTO\PedigreeDTO;
use AceDatagrid\Annotation as Grid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AcePedigree\Repository\DogRepository")
 * @ORM\Table(name="pedigree_dog")
 */
class Dog
{
    const SEX_MALE = 1;
    const SEX_FEMALE = 2;

    /**
     * @var array
     */
    protected $sexLabels = array(
        self::SEX_MALE => 'Male',
        self::SEX_FEMALE => 'Female',
    );

    /**
     * @var PedigreeDTO
     */
    protected $dto;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var DogStatistics
     *
     * @ORM\OneToMany(targetEntity="DogStatistics", mappedBy="dog")
     */
    protected $statistics;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    protected $callName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    protected $registration;

    /**
     * @var Kennel
     * 
     * @ORM\ManyToOne(targetEntity="Kennel", inversedBy="dogs")
     * @ORM\JoinColumn(name="kennelId", referencedColumnName="id", nullable=true)
     */
    protected $kennel;

    /**
     * @var Dog
     * 
     * @ORM\ManyToOne(targetEntity="Dog")
     * @ORM\JoinColumn(name="sireId", referencedColumnName="id", nullable=true)
     */
    protected $sire;

    /**
     * @var Dog
     * 
     * @ORM\ManyToOne(targetEntity="Dog")
     * @ORM\JoinColumn(name="damId", referencedColumnName="id", nullable=true)
     */
    protected $dam;

    /**
     * @var int
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $sex;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Person", inversedBy="dogsBred")
     * @ORM\JoinTable(name="pedigree_dog_breeder",
     *     joinColumns={@ORM\JoinColumn(name="dogId", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="breederId", referencedColumnName="id")}
     * )
     */
    protected $breeders;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Person", inversedBy="dogsOwned")
     * @ORM\JoinTable(name="pedigree_dog_owner",
     *     joinColumns={@ORM\JoinColumn(name="dogId", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="ownerId", referencedColumnName="id")}
     * )
     */
    protected $owners;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $birthYear;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $birthMonth;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $birthDay;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $deathYear;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $deathMonth;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $deathDay;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="birthCountryId", referencedColumnName="id", nullable=true)
     */
    protected $birthCountry;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="homeCountryId", referencedColumnName="id", nullable=true)
     */
    protected $homeCountry;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $height;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $weight;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $color;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $ofaNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $features;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $titles;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $notes;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Image", mappedBy="dog")
     */
    protected $images;

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
            throw new \BadMethodCallException('Call to undefined method ' . Dog::class . '::' . $method . '()');
        }

        return call_user_func_array([$this->getDTO(), $method], $args);
    }

    /**
     * @return PedigreeDTO
     */
    public function getDTO()
    {
        if (!isset($this->dto)) {
            $this->dto = new PedigreeDTO($this);
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
     * @return DogStatistics
     */
    public function getStatistics()
    {
        // Had to fool doctrine into thinking statistics
        // is M:1 in order to prevent extra queries
        return $this->statistics[0];
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
     * @return Kennel
     */
    public function getKennel()
    {
        return $this->kennel;
    }

    /**
     * @param Kennel $kennel
     */
    public function setKennel(Kennel $kennel = null)
    {
        $this->kennel = $kennel;
    }

    /**
     * @return Dog
     * @Grid\Header(label="Sire", sort={"sire.name", "name"})
     */
    public function getSire()
    {
        return $this->sire;
    }

    /**
     * @param Dog $sire
     */
    public function setSire(Dog $sire = null)
    {
        if ($sire && $sire->isDescendantOf($this->getDTO())) {
            throw new \Exception(sprintf('Dog \'%s\' cannot be sire of his ancestor \'%s\'', $sire, $this));
        }

        unset($this->dto);
        $this->sire = $sire;
    }

    /**
     * @return Dog
     * @Grid\Header(label="Dam", sort={"dam.name", "name"})
     */
    public function getDam()
    {
        return $this->dam;
    }

    /**
     * @param Dog $dam
     */
    public function setDam(Dog $dam = null)
    {
        if ($dam && $dam->isDescendantOf($this->getDTO())) {
            throw new \Exception(sprintf('Dog \'%s\' cannot be dam of her ancestor \'%s\'', $dam, $this));
        }

        unset($this->dto);
        $this->dam = $dam;
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

    /**
     * @param Image $image
     * @return boolean
     */
    public function hasImage(Image $image)
    {
        $this->images->contains($image);
    }

    /**
     * @param ArrayCollection $images
     */
    public function addImages(ArrayCollection $images)
    {
        foreach ($images as $image) {
            $this->images->add($image);
        }
    }

    /**
     * @param ArrayCollection $images
     */
    public function removeImages(ArrayCollection $images)
    {
        foreach ($images as $image) {
            $this->images->removeElement($image);
        }
    }

    /**
     * @return string
     * @Grid\Header(label="COI", sort={"statistics.inbreedingCoefficient", "statistics.averageCovariance"}, reverse=true)
     */
    public function getInbreedingCoefficientDisplay()
    {
        return round(100 * $this->getInbreedingCoefficient(), 2) . '%';
    }

    /**
     * @return string
     * @Grid\Header(label="MK", sort={"statistics.averageCovariance", "statistics.inbreedingCoefficient"}, reverse=true)
     */
    public function getAverageCovarianceDisplay()
    {
        return round(100 * $this->getAverageCovariance(), 2) . '%';
    }
}
