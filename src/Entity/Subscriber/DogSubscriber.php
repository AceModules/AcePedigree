<?php

namespace AcePedigree\Entity\Subscriber;

use AcePedigree\Entity\Dog;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class DogSubscriber implements EventSubscriber
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

        if ($entity instanceof Dog) {
            $entityManager->getRepository(Dog::class)->updateAncestry($entity);
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

        if ($entity instanceof Dog && (array_key_exists('sire', $changeSet) || array_key_exists('dam', $changeSet))) {
            $entityManager->getRepository(Dog::class)->updateAncestry($entity);
        }
    }
}