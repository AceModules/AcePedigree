<?php

namespace AcePedigree\Entity\Subscriber;

use AcePedigree\Entity\Animal;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class AnimalSubscriber implements EventSubscriber
{
    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entityManager = $args->getObjectManager();
        $entity = $args->getObject();

        if ($entity instanceof Animal) {
            $entityManager->getRepository(Animal::class)->updateAncestry($entity);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entityManager = $args->getObjectManager();
        $entity = $args->getObject();
        $changeSet = $entityManager->getUnitOfWork()->getEntityChangeSet($entity);

        if ($entity instanceof Animal && (array_key_exists('sire', $changeSet) || array_key_exists('dam', $changeSet))) {
            $entityManager->getRepository(Animal::class)->updateAncestry($entity);
        }
    }
}