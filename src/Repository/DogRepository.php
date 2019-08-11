<?php

namespace AcePedigree\Repository;

use AcePedigree\Entity\Dog;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Criteria;

class DogRepository extends EntityRepository
{
    /**
     * @param Dog $dog
     * @return array|null
     */
    function findByParent(Dog $dog)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('d')
            ->from(Dog::class, 'd')
            ->where('d.sire = :dog')
            ->orWhere('d.dam = :dog')
            ->orderBy('d.name', 'ASC')
            ->setParameter('dog', $dog)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Dog $dog
     * @return array|null
     */
    function findBySibling(Dog $dog)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('d')
            ->from(Dog::class, 'd')
            ->where('d.id != :dog')
            ->andWhere('d.sire = :sire OR d.dam = :dam')
            ->orderBy('d.name', 'ASC')
            ->setParameter('dog', $dog)
            ->setParameter('sire', $dog->getSire())
            ->setParameter('dam', $dog->getDam())
            ->getQuery()
            ->getResult();
    }
}
