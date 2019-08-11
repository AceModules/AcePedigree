<?php

namespace AcePedigree\Helper;

use AcePedigree\Entity;
use Zend\View\Helper\AbstractHelper;

class EntityLink extends AbstractHelper
{
    /**
     * @param object $entity
     * @return string
     */
    public function __invoke($entity = null)
    {
        if (is_a($entity, Entity\Dog::class)) {
            return '<a href="' . $this->view->url('ace-pedigree/dogs/view', ['id' => $entity->getId()]) . '">' . $entity->getName() . '</a>';
        }

        if (is_a($entity, Entity\Person::class)) {
            return '<a href="' . $this->view->url('ace-pedigree/persons/view', ['id' => $entity->getId()]) . '">' . $entity->getName() . '</a>';
        }

        return $entity;
    }
}
