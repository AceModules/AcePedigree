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

        $minBred = new Element\Number('minBred');
        $minBred->setAttribute('placeholder', 'Min. Animals Bred');
        $this->add($minBred);

        $minBredFilter = new Input('minBred');
        $minBredFilter->setRequired(false);
        $this->filter->add($minBredFilter);

        $maxBred = new Element\Number('maxBred');
        $maxBred->setAttribute('placeholder', 'Max. Animals Bred');
        $this->add($maxBred);

        $maxBredFilter = new Input('maxBred');
        $maxBredFilter->setRequired(false);
        $this->filter->add($maxBredFilter);

        $minOwned = new Element\Number('minOwned');
        $minOwned->setAttribute('placeholder', 'Min. Animals Owned');
        $this->add($minOwned);

        $minOwnedFilter = new Input('minOwned');
        $minOwnedFilter->setRequired(false);
        $this->filter->add($minOwnedFilter);

        $maxOwned = new Element\Number('maxOwned');
        $maxOwned->setAttribute('placeholder', 'Max. Animals Owned');
        $this->add($maxOwned);

        $maxOwnedFilter = new Input('maxOwned');
        $maxOwnedFilter->setRequired(false);
        $this->filter->add($maxOwnedFilter);

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
