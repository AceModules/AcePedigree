<?php

namespace AcePedigree\Entity\Repository;

use AcePedigree\Entity\Dog;
use AceDatagrid\Datagrid;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

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
     * @return array|null
     */
    function findByDescendant(Dog $dog)
    {
        $tableName = $this->getEntityManager()->getClassMetadata(Dog::class)->getTableName();

        $sql = 'WITH RECURSIVE cte AS (' .
            "SELECT x.* FROM {$tableName} x WHERE x.id = :dog UNION " .
            "SELECT a.* FROM {$tableName} a JOIN cte ON a.id = cte.sireId OR a.id = cte.damId" .
            ') SELECT cte.* FROM cte;';

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Dog::class, 'cte');

        return $this->getEntityManager()
            ->createNativeQuery($sql, $rsm)
            ->setParameter('dog', $dog)
            ->getResult();
    }

    /**
     * @param Dog $dog
     * @return array|null
     */
    function findByAncestor(Dog $dog)
    {
        $tableName = $this->getEntityManager()->getClassMetadata(Dog::class)->getTableName();

        $sql = 'WITH RECURSIVE cte AS (' .
            "SELECT x.* FROM {$tableName} x WHERE x.id = :dog UNION " .
            "SELECT d.* FROM {$tableName} d JOIN cte ON d.sireId = cte.id OR d.damId = cte.id" .
            ') SELECT cte.* FROM cte;';

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Dog::class, 'cte');

        return $this->getEntityManager()
            ->createNativeQuery($sql, $rsm)
            ->setParameter('dog', $dog)
            ->getResult();
    }

    /**
     * @param Dog $dog
     * @return array|null
     */
    function findByRelative(Dog $dog)
    {
        $tableName = $this->getEntityManager()->getClassMetadata(Dog::class)->getTableName();

        $sql = 'WITH RECURSIVE cte AS (' .
            "SELECT x.*, 1 AS isAncestor FROM {$tableName} x WHERE x.id = :dog UNION " .
            "SELECT a.*, 1 AS isAncestor FROM {$tableName} a JOIN cte ON (a.id = cte.sireId OR a.id = cte.damId) AND cte.isAncestor = 1 UNION " .
            "SELECT d.*, 0 AS isAncestor FROM {$tableName} d JOIN cte ON d.sireId = cte.id OR d.damId = cte.id" .
            ") SELECT DISTINCT r.* FROM cte JOIN {$tableName} r ON r.id = cte.id;";

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Dog::class, 'cte');

        return $this->getEntityManager()
            ->createNativeQuery($sql, $rsm)
            ->setParameter('dog', $dog)
            ->getResult();
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
