<?php

namespace AcePedigree\Form;

use AcePedigree\Entity\Animal;
use Laminas\Form\Element;
use Laminas\Form\Form;
use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator;

class AdvancedSearch extends Form
{
    public function __construct()
    {
        parent::__construct('advancedSearch');

        $this->setAttribute('method', 'GET');

        $this->filter = new InputFilter();

        $name = new Element\Text('name');
        $name->setAttribute('placeholder', 'Registered Name');
        $this->add($name);

        $callName = new Element\Text('callName');
        $callName->setAttribute('placeholder', 'Call Name');
        $this->add($callName);

        // Breeders
        // Owners
        // House
        // Sire
        // Dam

        $sex = new Element\Select('sex');
        $sex->setValueOptions([Animal::SEX_MALE => 'Male', Animal::SEX_FEMALE => 'Female']);
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

        $color = new Element\Select('color');
        $color->setValueOptions(\AcePedigree\ANIMAL_COLORS);
        $color->setEmptyOption('Select a Color');
        $this->add($color);

        $colorFilter = new Input('color');
        $colorFilter->setRequired(false);
        $this->filter->add($colorFilter);

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

        $minCOI = new Element\Number('minCOI');
        $minCOI->setAttribute('placeholder', 'Min. COI %');
        $minCOI->setAttribute('step', '0.1');
        $this->add($minCOI);

        $minCOIFilter = new Input('minCOI');
        $minCOIFilter->setRequired(false);
        $minCOIFilter->getValidatorChain()->attach(new Validator\Between(['min' => 0, 'max' => 100]));
        $this->filter->add($minCOIFilter);

        $maxCOI = new Element\Number('maxCOI');
        $maxCOI->setAttribute('placeholder', 'Max. COI %');
        $maxCOI->setAttribute('step', '0.1');
        $this->add($maxCOI);

        $maxCOIFilter = new Input('maxCOI');
        $maxCOIFilter->setRequired(false);
        $maxCOIFilter->getValidatorChain()->attach(new Validator\Between(['min' => 0, 'max' => 100]));
        $this->filter->add($maxCOIFilter);

        $minMK = new Element\Number('minMK');
        $minMK->setAttribute('placeholder', 'Min. MK %');
        $minMK->setAttribute('step', '0.1');
        $this->add($minMK);

        $minMKFilter = new Input('minMK');
        $minMKFilter->setRequired(false);
        $minMKFilter->getValidatorChain()->attach(new Validator\Between(['min' => 0, 'max' => 100]));
        $this->filter->add($minMKFilter);

        $maxMK = new Element\Number('maxMK');
        $maxMK->setAttribute('placeholder', 'Max. MK %');
        $maxMK->setAttribute('step', '0.1');
        $this->add($maxMK);

        $maxMKFilter = new Input('maxMK');
        $maxMKFilter->setRequired(false);
        $maxMKFilter->getValidatorChain()->attach(new Validator\Between(['min' => 0, 'max' => 100]));
        $this->filter->add($maxMKFilter);

        $minRP = new Element\Number('minRP');
        $minRP->setAttribute('placeholder', 'Min. RP %');
        $minRP->setAttribute('step', '0.1');
        $this->add($minRP);

        $minRPFilter = new Input('minRP');
        $minRPFilter->setRequired(false);
        $minRPFilter->getValidatorChain()->attach(new Validator\Between(['min' => 0, 'max' => 100]));
        $this->filter->add($minRPFilter);

        $maxRP = new Element\Number('maxRP');
        $maxRP->setAttribute('placeholder', 'Max. RP %');
        $maxRP->setAttribute('step', '0.1');
        $this->add($maxRP);

        $maxRPFilter = new Input('maxRP');
        $maxRPFilter->setRequired(false);
        $maxRPFilter->getValidatorChain()->attach(new Validator\Between(['min' => 0, 'max' => 100]));
        $this->filter->add($maxRPFilter);

        $buttons = new Form('buttons');
        $buttons->setOption('layout', \TwbsHelper\Form\View\Helper\Form::LAYOUT_INLINE);
        $buttons->setAttribute('class', 'form-group');

        $submit = new Element\Submit('submit');
        $submit->setAttribute('class', 'btn-primary float-right');
        $submit->setOption('icon', 'fas fa-search');
        $submit->setLabel('Search');
        $buttons->add($submit);

        $this->add($buttons);
    }
}
