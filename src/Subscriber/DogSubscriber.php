<?php

namespace AcePedigree\Subscriber;

use AcePedigree\Entity\Kinship;
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
            Events::postLoad,
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
            $entityManager->getRepository(Kinship::class)->updateAncestry($entity);
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
            $entityManager->getRepository(Kinship::class)->updateAncestry($entity);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entityManager = $args->getObjectManager();
        $entity = $args->getObject();

        if ($entity instanceof Dog) {
            $callback = [$entityManager->getRepository(Kinship::class), 'getAverageCovariance'];
            $entity->setAverageCovariance($callback);
        }
    }
}