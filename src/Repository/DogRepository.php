<?php

namespace AcePedigree\Repository;

use AcePedigree\Entity\Dog;
use AceDatagrid\Datagrid;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

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

    /**
     * @param Datagrid $datagrid
     * @param array $searchParams
     * @param string $sortParam
     * @return QueryBuilder
     */
    function createSearchQueryBuilder(Datagrid $datagrid, array $searchParams, &$sortParam = '')
    {
        $queryBuilder = $datagrid->createSearchQueryBuilder(null, $sortParam);
        $searchParams = array_filter($searchParams);

        foreach ($searchParams as $property => $searchParam) {
            switch ($property) {
                case 'sex':
                    $queryBuilder->andWhere($queryBuilder->expr()->eq('entity.sex', $searchParam));
                    break;

                case 'minHeight':
                    $queryBuilder->andWhere($queryBuilder->expr()->gte('entity.height', $searchParam));
                    break;

                case 'maxHeight':
                    $queryBuilder->andWhere($queryBuilder->expr()->lte('entity.height', $searchParam));
                    break;

                case 'minWeight':
                    $queryBuilder->andWhere($queryBuilder->expr()->gte('entity.weight', $searchParam));
                    break;

                case 'maxWeight':
                    $queryBuilder->andWhere($queryBuilder->expr()->lte('entity.weight', $searchParam));
                    break;

                default:
                    $metadata = $this->getEntityManager()->getClassMetadata(Dog::class);
                    $reflection = $metadata->getReflectionClass();

                    if ($reflection->hasProperty($property)) {
                        $searchParam = strtolower(trim(preg_replace('/[^a-z0-9! -]+/i', '', $searchParam)));
                        $searchParamParts = array_filter(explode(' ', $searchParam));

                        foreach ($searchParamParts as $searchParamPart) {
                            $queryBuilder->andWhere(
                                $queryBuilder->expr()->like('entity.' . $property, $queryBuilder->expr()->literal('%' . $searchParamPart . '%'))
                            );
                        }
                    }
                    break;
            }
        }

        return $queryBuilder;
    }
}
