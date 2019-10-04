<?php

namespace AcePedigree\Entity\Repository;

use AcePedigree\Entity\Person;
use AceDatagrid\Datagrid;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class PersonRepository extends EntityRepository
{
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

        if (isset($searchParams['minDogsBred']) || isset($searchParams['maxDogsBred'])) {
            $queryBuilder->leftJoin('entity.dogsBred', 'dogsBred')->groupBy('entity.id');
        }

        if (isset($searchParams['minDogsOwned']) || isset($searchParams['maxDogsOwned'])) {
            $queryBuilder->leftJoin('entity.dogsOwned', 'dogsOwned')->groupBy('entity.id');
        }

        foreach ($searchParams as $property => $searchParam) {
            switch ($property) {
                case 'minDogsBred':
                    $queryBuilder->andHaving($queryBuilder->expr()->gte('count(dogsBred.id)', $searchParam));
                    break;

                case 'maxDogsBred':
                    $queryBuilder->andHaving($queryBuilder->expr()->lte('count(dogsBred.id)', $searchParam));
                    break;

                case 'minDogsOwned':
                    $queryBuilder->andHaving($queryBuilder->expr()->gte('count(dogsOwned.id)', $searchParam));
                    break;

                case 'maxDogsOwned':
                    $queryBuilder->andHaving($queryBuilder->expr()->lte('count(dogsOwned.id)', $searchParam));
                    break;

                default:
                    $metadata = $this->getEntityManager()->getClassMetadata(Person::class);
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
