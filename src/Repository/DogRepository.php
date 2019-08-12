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

    /**
     * @param Dog $dog
     * @param int $maxGen
     * @return array|null
     */
    function findByDescendant(Dog $dog, $maxGen = 3)
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('g0')
            ->from(Dog::class, 'g0')
            ->setParameter('dog', $dog);

        for ($g = 0; $g < $maxGen; $g++) {
            $n = $g + 1;
            $queryBuilder
                ->leftJoin(Dog::class, "g{$n}", 'WITH', "g{$g}.id = g{$n}.sire OR g{$g}.id = g{$n}.dam")
                ->orWhere("g{$n} = :dog");
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
