<?php

namespace AcePedigree\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;
use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;

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
        $minBred->setAttribute('placeholder', sprintf('Min. %s Bred', \AcePedigree\ANIMAL_PLURAL));
        $this->add($minBred);

        $minBredFilter = new Input('minBred');
        $minBredFilter->setRequired(false);
        $this->filter->add($minBredFilter);

        $maxBred = new Element\Number('maxBred');
        $maxBred->setAttribute('placeholder', sprintf('Max. %s Bred', \AcePedigree\ANIMAL_PLURAL));
        $this->add($maxBred);

        $maxBredFilter = new Input('maxBred');
        $maxBredFilter->setRequired(false);
        $this->filter->add($maxBredFilter);

        $minOwned = new Element\Number('minOwned');
        $minOwned->setAttribute('placeholder', sprintf('Min. %s Owned', \AcePedigree\ANIMAL_PLURAL));
        $this->add($minOwned);

        $minOwnedFilter = new Input('minOwned');
        $minOwnedFilter->setRequired(false);
        $this->filter->add($minOwnedFilter);

        $maxOwned = new Element\Number('maxOwned');
        $maxOwned->setAttribute('placeholder', sprintf('Max. %s Owned', \AcePedigree\ANIMAL_PLURAL));
        $this->add($maxOwned);

        $maxOwnedFilter = new Input('maxOwned');
        $maxOwnedFilter->setRequired(false);
        $this->filter->add($maxOwnedFilter);

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
