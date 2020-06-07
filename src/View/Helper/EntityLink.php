<?php

namespace AcePedigree\View\Helper;

use AcePedigree\Entity;
use Laminas\View\Helper\AbstractHelper;

class EntityLink extends AbstractHelper
{
    /**
     * @param object $entity
     * @return string
     */
    public function __invoke($entity = null)
    {
        if (is_a($entity, Entity\Animal::class) && $entity->getId()) {
            return '<a href="' . str_replace('/index', '', $this->view->url('ace-pedigree/animals/view', ['id' => $entity->getId()])) . '">' . $entity->getName() . '</a>';
        }

        if (is_a($entity, Entity\Person::class) && $entity->getId()) {
            return '<a href="' . str_replace('/index', '', $this->view->url('ace-pedigree/persons/view', ['id' => $entity->getId()])) . '">' . $entity->getName() . '</a>';
        }

        return $entity;
    }
}
