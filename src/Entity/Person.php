<?php

namespace AcePedigree\Entity;

use AceDatagrid\Annotation as Grid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Form\Annotation as Form;

/**
 * @ORM\Entity(repositoryClass="AcePedigree\Entity\Repository\PersonRepository")
 * @ORM\Table(name="pedigree_person")
 * @Form\Name("person")
 * @Form\Hydrator("Zend\Hydrator\ClassMethods")
 * @Grid\Title(singular="Person", plural="Persons")
 */
class Person
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
     * @Grid\Suggest()
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Email")
     * @Form\Options({"label": "Email"})
     * @Form\Filter({"name": "ToNull"})
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Url")
     * @Form\Options({"label": "Website"})
     * @Form\Filter({"name": "ToNull"})
     */
    protected $website;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Text")
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
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Text")
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
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Text")
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
     * @Form\Required(false)
     * @Form\Type("Zend\Form\Element\Text")
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
     * @Form\Required(false)
     * @Form\Type("AceAdmin\Form\Element\ObjectLiveSearch")
     * @Form\Options({"label": "Country", "empty_option": "Select a Country",
     *     "find_method": {
     *         "name": "findBy",
     *         "params": {"criteria": {}, "orderBy": {"name": "ASC"}}
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
     * @Form\Required(false)
     * @Form\Type("AceAdmin\Form\Element\Phone")
     * @Form\Options({"label": "Phone"})
     * @Form\Filter({"name": "ToNull"})
     */
    protected $phone;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Dog", mappedBy="breeders")
     * @Form\Exclude()
     */
    protected $dogsBred;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Dog", mappedBy="owners")
     * @Form\Exclude()
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
     * @return int
     * @Grid\Header(label="Dogs Bred", sort={"count(dogsBred.id)"})
     */
    public function getDogsBredCount()
    {
        return count($this->dogsBred);
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

    /**
     * @return int
     * @Grid\Header(label="Dogs Owned", sort={"count(dogsOwned.id)"})
     */
    public function getDogsOwnedCount()
    {
        return count($this->dogsOwned);
    }
}
