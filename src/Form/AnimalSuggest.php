<?php

namespace AcePedigree\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class AnimalSuggest extends Form
{
    public function __construct()
    {
        parent::__construct('animalSuggest');

        $this->setAttribute('method', 'GET');

        $sire = new Element\Hidden('sire');
        $this->add($sire);

        $dam = new Element\Hidden('dam');
        $this->add($dam);

        $q = new Element\Select('q');
        $q->setAttributes([
            'class'            => 'selectpicker',
            'data-live-search' => true,
            'data-min-length'  => 3,
        ]);
        $this->add($q);

        $buttons = new Form('buttons');
        $buttons->setOption('twb-layout', 'inline');
        $buttons->setAttribute('class', 'form-group');

        $submit = new Element\Submit('submit');
        $submit->setAttribute('value', 'Save');
        $submit->setAttribute('class', 'btn-primary pull-right');
        $buttons->add($submit);

        $cancel = new Element\Submit('cancel');
        $cancel->setAttribute('value', 'Cancel');
        $cancel->setAttribute('formnovalidate', true);
        $cancel->setAttribute('data-dismiss', 'modal');
        $cancel->setAttribute('class', 'btn-warning pull-right');
        $buttons->add($cancel);

        $this->add($buttons);
    }
}