<?php

namespace AcePedigree\Entity\Repository;

use AcePedigree\Entity\Animal;
use AceDatagrid\Datagrid;
use AcePedigree\Entity\AnimalKinship;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class AnimalRepository extends EntityRepository
{
    /**
     * @param Animal $animal
     * @return array|null
     */
    function findByParent(Animal $animal)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('d')
            ->from(Animal::class, 'd')
            ->where('d.sire = :animal')
            ->orWhere('d.dam = :animal')
            ->orderBy('d.name', 'ASC')
            ->setParameter('animal', $animal)
            ->getQuery()
            ->enableResultCache()
            ->getResult();
    }

    /**
     * @param Animal $animal
     * @return array|null
     */
    function findBySibling(Animal $animal)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('d')
            ->from(Animal::class, 'd')
            ->where('d.id != :animal')
            ->andWhere('d.sire = :sire OR d.dam = :dam')
            ->orderBy('d.name', 'ASC')
            ->setParameter('animal', $animal)
            ->setParameter('sire', $animal->getSire())
            ->setParameter('dam', $animal->getDam())
            ->getQuery()
            ->enableResultCache()
            ->getResult();
    }

    /**
     * @param Animal $animal
     * @return array|null
     */
    function findByDescendant(Animal $animal)
    {
        $sql = 'WITH RECURSIVE cte AS (' .
            'SELECT x.* FROM pedigree_animal x WHERE x.id = :animal UNION ' .
            'SELECT a.* FROM pedigree_animal a JOIN cte ON a.id = cte.sireId OR a.id = cte.damId' .
            ') SELECT cte.* FROM cte;';

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Animal::class, 'cte');

        return $this->getEntityManager()
            ->createNativeQuery($sql, $rsm)
            ->setParameter('animal', $animal)
            ->enableResultCache()
            ->getResult();
    }

    /**
     * @param Animal $animal
     * @return array|null
     */
    function findByAncestor(Animal $animal)
    {
        $sql = 'WITH RECURSIVE cte AS (' .
            'SELECT x.* FROM pedigree_animal x WHERE x.id = :animal UNION ' .
            'SELECT d.* FROM pedigree_animal d JOIN cte ON d.sireId = cte.id OR d.damId = cte.id' .
            ') SELECT cte.* FROM cte;';

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Animal::class, 'cte');

        return $this->getEntityManager()
            ->createNativeQuery($sql, $rsm)
            ->setParameter('animal', $animal)
            ->enableResultCache()
            ->getResult();
    }

    /**
     * @param Animal $animal
     * @return array|null
     */
    function findByRelative(Animal $animal)
    {
        $sql = 'WITH RECURSIVE cte AS (' .
            'SELECT x.*, 1 AS isAncestor FROM pedigree_animal x WHERE x.id = :animal UNION ' .
            'SELECT a.*, 1 AS isAncestor FROM pedigree_animal a JOIN cte ON (a.id = cte.sireId OR a.id = cte.damId) AND cte.isAncestor = 1 UNION ' .
            'SELECT d.*, 0 AS isAncestor FROM pedigree_animal d JOIN cte ON d.sireId = cte.id OR d.damId = cte.id' .
            ') SELECT DISTINCT r.* FROM cte JOIN pedigree_animal r ON r.id = cte.id;';

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Animal::class, 'cte');

        return $this->getEntityManager()
            ->createNativeQuery($sql, $rsm)
            ->setParameter('animal', $animal)
            ->disableResultCache() // NOTE Not cached
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

                case 'minCOI':
                    $queryBuilder->andWhere($queryBuilder->expr()->gte('entity.inbreedingCoefficient', $searchParam / 100));
                    break;

                case 'maxCOI':
                    $queryBuilder->andWhere($queryBuilder->expr()->lte('entity.inbreedingCoefficient', $searchParam / 100));
                    break;

                case 'minMK':
                    $queryBuilder->andWhere($queryBuilder->expr()->gte('entity.averageCovariance', $searchParam / 100));
                    break;

                case 'maxMK':
                    $queryBuilder->andWhere($queryBuilder->expr()->lte('entity.averageCovariance', $searchParam / 100));
                    break;

                case 'minRP':
                    $queryBuilder->andWhere($queryBuilder->expr()->gte('entity.relativePopularity', $searchParam / 100));
                    break;

                case 'maxRP':
                    $queryBuilder->andWhere($queryBuilder->expr()->lte('entity.relativePopularity', $searchParam / 100));
                    break;

                case 'mate':
                    $queryBuilder->leftJoin(AnimalKinship::class, 'entity_kinship', Join::WITH, $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->andX(
                            $queryBuilder->expr()->eq('entity_kinship.animal1', $searchParam),
                            $queryBuilder->expr()->eq('entity_kinship.animal2', 'entity')
                        ),
                        $queryBuilder->expr()->andX(
                            $queryBuilder->expr()->eq('entity_kinship.animal1', 'entity'),
                            $queryBuilder->expr()->eq('entity_kinship.animal2', $searchParam)
                        )
                    ));
                    break;

                case 'minRC':
                    if (isset($searchParams['mate'])) {
                        $queryBuilder->andWhere($queryBuilder->expr()->gte('entity_kinship.covariance', $searchParam / 100));
                    }
                    break;

                case 'maxRC':
                    if (isset($searchParams['mate'])) {
                        $queryBuilder->andWhere($queryBuilder->expr()->orX(
                            $queryBuilder->expr()->lte('entity_kinship.covariance', $searchParam / 100),
                            $queryBuilder->expr()->isNull('entity_kinship.covariance')
                        ));
                    }
                    break;

                default:
                    $metadata = $this->getEntityManager()->getClassMetadata(Animal::class);
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

    /**
     * @param Animal $animal
     */
    public function updateAncestry(Animal $animal)
    {
        $this->getEntityManager()
            ->getConnection()
            ->executeQuery(
                'DELETE FROM pedigree_animal_kinship WHERE animal1Id = :animal OR animal2Id = :animal',
                [':animal' => $animal->getId()]
            );

        $relatives = $this->findByRelative($animal); // NOTE Not cached
        $placeholders = [];
        $values = [];
        $types = [];

        foreach ($relatives as $relative) {
            $placeholders[] = '(?)';
            $values[] = [
                min($animal->getId(), $relative->getId()),
                max($animal->getId(), $relative->getId()),
                $animal->getCovarianceWith($relative->getDTO()),
                ($animal->getId() != $relative->getId() && $animal->isDescendantOf($relative->getDTO()) ? $relative->getId() :
                    ($animal->getId() != $relative->getId() && $relative->isDescendantOf($animal->getDTO()) ? $animal->getId() : null))
            ];
            $types[] = Connection::PARAM_STR_ARRAY;
        }

        $this->getEntityManager()
            ->getConnection()
            ->executeQuery(
                'INSERT INTO pedigree_animal_kinship (animal1Id, animal2Id, covariance, ancestorId) VALUES ' . implode(', ', $placeholders),
                $values,
                $types
            );

        // This could potentially be slow in a very large db
        $this->getEntityManager()
            ->getConnection()
            ->executeQuery('UPDATE pedigree_animal d JOIN pedigree_animal_statistics s ON s.animalId = d.id SET d.inbreedingCoefficient = s.inbreedingCoefficient, d.averageCovariance = s.averageCovariance, d.relativePopularity = s.relativePopularity');
    }

    /**
     * @param Animal $animal
     * @return array
     */
    public function getRelationshipCoefficientData(Animal $animal)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('CASE WHEN k.animal1 = :animal THEN IDENTITY(k.animal2) ELSE IDENTITY(k.animal1) END AS animalId, k.covariance')
            ->from(AnimalKinship::class, 'k')
            ->where('k.animal1 = :animal')
            ->orWhere('k.animal2 = :animal')
            ->setParameter('animal', $animal)
            ->getQuery()
            ->enableResultCache()
            ->getArrayResult();
    }

    /**
     * @return array
     */
    public function getForceDirectedKinshipData()
    {
        $nodes = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('d.id, d.name, d.averageCovariance AS value')
            ->from(Animal::class, 'd')
            ->getQuery()
            ->enableResultCache()
            ->getArrayResult();

        $links = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('IDENTITY(k.animal1) AS source, IDENTITY(k.animal2) AS target, 1 - k.covariance AS distance')
            ->from(AnimalKinship::class, 'k')
            ->where('k.animal1 != k.animal2')
            ->getQuery()
            ->enableResultCache()
            ->getArrayResult();

        array_walk($links, function (&$link) {
            $link['source'] = (int) $link['source'];
            $link['target'] = (int) $link['target'];
            $link['distance'] = (float) $link['distance'];
        });

        return [$nodes, $links];
    }
}
