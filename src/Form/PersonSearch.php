<?php

namespace AcePedigree\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;

class PersonSearch extends Form
{
    public function __construct()
    {
        parent::__construct('personSearch');

        $this->setAttribute('method', 'GET');
        $this->setAttribute('action', '/pedigree/persons');

        $this->filter = new InputFilter();

        $name = new Element\Text('name');
        $name->setAttribute('placeholder', 'Name');
        $this->add($name);

        $email = new Element\Text('email');
        $email->setAttribute('placeholder', 'Email');
        $this->add($email);

        $website = new Element\Text('website');
        $website->setAttribute('placeholder', 'Website');
        $this->add($website);

        // Country

        $minDogsBred = new Element\Number('minDogsBred');
        $minDogsBred->setAttribute('placeholder', 'Min. Dogs Bred');
        $this->add($minDogsBred);

        $minDogsBredFilter = new Input('minDogsBred');
        $minDogsBredFilter->setRequired(false);
        $this->filter->add($minDogsBredFilter);

        $maxDogsBred = new Element\Number('maxDogsBred');
        $maxDogsBred->setAttribute('placeholder', 'Max. Dogs Bred');
        $this->add($maxDogsBred);

        $maxDogsBredFilter = new Input('maxDogsBred');
        $maxDogsBredFilter->setRequired(false);
        $this->filter->add($maxDogsBredFilter);

        $minDogsOwned = new Element\Number('minDogsOwned');
        $minDogsOwned->setAttribute('placeholder', 'Min. Dogs Owned');
        $this->add($minDogsOwned);

        $minDogsOwnedFilter = new Input('minDogsOwned');
        $minDogsOwnedFilter->setRequired(false);
        $this->filter->add($minDogsOwnedFilter);

        $maxDogsOwned = new Element\Number('maxDogsOwned');
        $maxDogsOwned->setAttribute('placeholder', 'Max. Dogs Owned');
        $this->add($maxDogsOwned);

        $maxDogsOwnedFilter = new Input('maxDogsOwned');
        $maxDogsOwnedFilter->setRequired(false);
        $this->filter->add($maxDogsOwnedFilter);

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
