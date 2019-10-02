<?php

namespace AcePedigree\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="pedigree_person")
 */
class Person
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
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $website;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $street;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $region;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    protected $postalCode;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="countryId", referencedColumnName="id", nullable=true)
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $phone;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Dog", mappedBy="breeders")
     */
    protected $dogsBred;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Dog", mappedBy="owners")
     */
    protected $dogsOwned;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dogsBred = new ArrayCollection();
        $this->dogsOwned = new ArrayCollection();
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
     * @return string
     */
    public function getNameLink($renderer)
    {
        return '<a href="' . $renderer->url('ace-pedigree/persons/view', ['id' => $this->id]) . '">' . $this->name . '</a>';
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
     * @return Country
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
    public function getDogsBred()
    {
        return $this->dogsBred;
    }

    /**
     * @param Dog $dog
     * @return boolean
     */
    public function hasDogBred(Dog $dog)
    {
        $this->dogsBred->contains($dog);
    }

    /**
     * @param ArrayCollection $dogs
     */
    public function addDogsBred(ArrayCollection $dogs)
    {
        foreach ($dogs as $dog) {
            $this->dogsBred->add($dog);
        }
    }

    /**
     * @param ArrayCollection $dogs
     */
    public function removeDogsBred(ArrayCollection $dogs)
    {
        foreach ($dogs as $dog) {
            $this->dogsBred->removeElement($dog);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getDogsOwned()
    {
        return $this->dogsOwned;
    }

    /**
     * @param Dog $dog
     * @return boolean
     */
    public function hasDogOwned(Dog $dog)
    {
        $this->dogsOwned->contains($dog);
    }

    /**
     * @param ArrayCollection $dogs
     */
    public function addDogsOwned(ArrayCollection $dogs)
    {
        foreach ($dogs as $dog) {
            $this->dogsOwned->add($dog);
        }
    }

    /**
     * @param ArrayCollection $dogs
     */
    public function removeDogsOwned(ArrayCollection $dogs)
    {
        foreach ($dogs as $dog) {
            $this->dogsOwned->removeElement($dog);
        }
    }
}
