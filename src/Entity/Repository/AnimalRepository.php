<?php

namespace AcePedigree\Entity\Repository;

use AcePedigree\Entity\Animal;
use AceDatagrid\Datagrid;
use AcePedigree\Entity\AnimalKinship;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
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

        $relatives = $this->getEntityManager()->getRepository(Animal::class)->findByRelative($animal);
        $placeholders = [];
        $values = [];
        $types = [];

        foreach ($relatives as $relative) {
            $placeholders[] = '(?)';
            $values[] = [
                min($animal->getId(), $relative->getId()),
                max($animal->getId(), $relative->getId()),
                $animal->getCovarianceWith($relative->getDTO()),
            ];
            $types[] = Connection::PARAM_STR_ARRAY;
        }

        $this->getEntityManager()
            ->getConnection()
            ->executeQuery(
                'INSERT INTO pedigree_animal_kinship (animal1Id, animal2Id, covariance) VALUES ' . implode(', ', $placeholders),
                $values,
                $types
            );

        // This could potentially be slow in a very large db
        $this->getEntityManager()
            ->getConnection()
            ->executeQuery('UPDATE pedigree_animal d JOIN pedigree_animal_statistics s ON s.animalId = d.id SET d.inbreedingCoefficient = s.inbreedingCoefficient, d.averageCovariance = s.averageCovariance');
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
            ->getArrayResult();

        $links = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('IDENTITY(k.animal1) AS source, IDENTITY(k.animal2) AS target, 1 - k.covariance AS distance')
            ->from(AnimalKinship::class, 'k')
            ->where('k.animal1 != k.animal2')
            ->getQuery()
            ->getArrayResult();

        array_walk($links, function (&$link) {
            $link['source'] = (int) $link['source'];
            $link['target'] = (int) $link['target'];
            $link['distance'] = (float) $link['distance'];
        });

        return [$nodes, $links];
    }
}
