<?php

namespace AcePedigree\Form;

use AcePedigree\Entity\Dog;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class AdvancedSearch extends Form
{
    public function __construct()
    {
        parent::__construct('advancedSearch');

        $this->setAttribute('method', 'GET');
        $this->setAttribute('action', '/pedigree/dogs');

        $this->filter = new InputFilter();

        $name = new Element\Text('name');
        $name->setAttribute('placeholder', 'Registered Name');
        $this->add($name);

        $callName = new Element\Text('callName');
        $callName->setAttribute('placeholder', 'Call Name');
        $this->add($callName);

        // Breeders
        // Owners
        // Kennel
        // Sire
        // Dam

        $sex = new Element\Select('sex');
        $sex->setValueOptions([Dog::SEX_MALE => 'Male', Dog::SEX_FEMALE => 'Female']);
        $sex->setEmptyOption('Select a Sex');
        $this->add($sex);

        $sexFilter = new Input('sex');
        $sexFilter->setRequired(false);
        $this->filter->add($sexFilter);

        $birthYear = new Element\Number('birthYear');
        $birthYear->setAttribute('placeholder', 'Birth Year');
        $this->add($birthYear);

        $birthYearFilter = new Input('birthYear');
        $birthYearFilter->setRequired(false);
        $birthYearFilter->getValidatorChain()->attach(new Validator\Between(['min' => 1900, 'max' => date('Y')]));
        $this->filter->add($birthYearFilter);

        $deathYear = new Element\Number('deathYear');
        $deathYear->setAttribute('placeholder', 'Death Year');
        $this->add($deathYear);

        $deathYearFilter = new Input('deathYear');
        $deathYearFilter->setRequired(false);
        $deathYearFilter->getValidatorChain()->attach(new Validator\Between(['min' => 1900, 'max' => date('Y')]));
        $this->filter->add($deathYearFilter);

        // Land of Birth
        // Land of Standing

        $minHeight = new Element\Number('minHeight');
        $minHeight->setAttribute('placeholder', 'Min. Height');
        $this->add($minHeight);

        $minHeightFilter = new Input('minHeight');
        $minHeightFilter->setRequired(false);
        $minHeightFilter->getValidatorChain()->attach(new Validator\Between(['min' => 6, 'max' => 30]));
        $this->filter->add($minHeightFilter);

        $maxHeight = new Element\Number('maxHeight');
        $maxHeight->setAttribute('placeholder', 'Max. Height');
        $this->add($maxHeight);

        $maxHeightFilter = new Input('maxHeight');
        $maxHeightFilter->setRequired(false);
        $maxHeightFilter->getValidatorChain()->attach(new Validator\Between(['min' => 6, 'max' => 30]));
        $this->filter->add($maxHeightFilter);

        $minWeight = new Element\Number('minWeight');
        $minWeight->setAttribute('placeholder', 'Min. Weight');
        $this->add($minWeight);

        $minWeightFilter = new Input('minWeight');
        $minWeightFilter->setRequired(false);
        $minWeightFilter->getValidatorChain()->attach(new Validator\Between(['min' => 1, 'max' => 300]));
        $this->filter->add($minWeightFilter);

        $maxWeight = new Element\Number('maxWeight');
        $maxWeight->setAttribute('placeholder', 'Max. Weight');
        $this->add($maxWeight);

        $maxWeightFilter = new Input('maxWeight');
        $maxWeightFilter->setRequired(false);
        $maxWeightFilter->getValidatorChain()->attach(new Validator\Between(['min' => 1, 'max' => 300]));
        $this->filter->add($maxWeightFilter);

        // Color

        $features = new Element\Text('features');
        $features->setAttribute('placeholder', 'Features');
        $this->add($features);

        $titles = new Element\Text('titles');
        $titles->setAttribute('placeholder', 'Titles');
        $this->add($titles);

        $registration = new Element\Text('registration');
        $registration->setAttribute('placeholder', 'Registration');
        $this->add($registration);

        // OFA

        $notes = new Element\Text('notes');
        $notes->setAttribute('placeholder', 'Notes');
        $this->add($notes);

        // Siblings
        // Offspring
        // Statistics

        $buttons = new Form('buttons');
        $buttons->setOption('twb-layout', 'inline');
        $buttons->setAttribute('class', 'form-group');

        $submit = new Element\Submit('submit');
        $submit->setAttribute('class', 'btn-primary pull-right');
        $submit->setOption('glyphicon', 'search');
        $submit->setLabel('Search');
        $buttons->add($submit);

        $this->add($buttons);
    }
}
