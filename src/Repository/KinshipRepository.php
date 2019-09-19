<?php

namespace AcePedigree\Repository;

use AcePedigree\Entity\Dog;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;

class KinshipRepository extends EntityRepository
{
    /**
     * @param Dog $dog
     */
    public function updateAncestry(Dog $dog)
    {
        $this->getEntityManager()
            ->getConnection()
            ->executeQuery(
                'DELETE FROM pedigree_kinship WHERE dog1Id = :dog OR dog2Id = :dog',
                [':dog' => $dog->getId()]
            );

        $relatives = $this->getEntityManager()->getRepository(Dog::class)->findByRelative($dog);
        $placeholders = [];
        $values = [];
        $types = [];

        foreach ($relatives as $relative) {
            $placeholders[] = '(?)';
            $values[] = [
                min($dog->getId(), $relative->getId()),
                max($dog->getId(), $relative->getId()),
                $dog->getCovarianceWith($relative->getDTO()),
            ];
            $types[] = Connection::PARAM_STR_ARRAY;
        }

        $this->getEntityManager()
            ->getConnection()
            ->executeQuery(
                'INSERT INTO pedigree_kinship (dog1Id, dog2Id, covariance) VALUES ' . implode(', ', $placeholders),
                $values,
                $types
            );
    }

    /**
     * @param Dog $dog
     * @return float
     */
    public function getAverageCovariance(Dog $dog)
    {
        return $this->getEntityManager()
            ->getConnection()
            ->fetchColumn(
                'SELECT avgCovariance FROM pedigree_kinship_avg WHERE dogId = :dog',
                [':dog' => $dog->getId()]
            );
    }
}
