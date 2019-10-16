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

        if (isset($searchParams['minBred']) || isset($searchParams['maxBred'])) {
            $queryBuilder->leftJoin('entity.animalsBred', 'animalsBred')->groupBy('entity.id');
        }

        if (isset($searchParams['minOwned']) || isset($searchParams['maxOwned'])) {
            $queryBuilder->leftJoin('entity.animalsOwned', 'animalsOwned')->groupBy('entity.id');
        }

        foreach ($searchParams as $property => $searchParam) {
            switch ($property) {
                case 'minBred':
                    $queryBuilder->andHaving($queryBuilder->expr()->gte('count(animalsBred.id)', $searchParam));
                    break;

                case 'maxBred':
                    $queryBuilder->andHaving($queryBuilder->expr()->lte('count(animalsBred.id)', $searchParam));
                    break;

                case 'minOwned':
                    $queryBuilder->andHaving($queryBuilder->expr()->gte('count(animalsOwned.id)', $searchParam));
                    break;

                case 'maxOwned':
                    $queryBuilder->andHaving($queryBuilder->expr()->lte('count(animalsOwned.id)', $searchParam));
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
