<?php

namespace AcePedigree\Repository;

use AcePedigree\Entity\Ancestry;
use AcePedigree\Entity\Dog;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;

class AncestryRepository extends EntityRepository
{
    /**
     * @param Dog $dog
     */
    public function updateAncestry(Dog $dog)
    {
        $tableName = $this->getEntityManager()->getClassMetadata(Ancestry::class)->getTableName();

        $this->getEntityManager()
            ->getConnection()
            ->executeQuery(
                "DELETE FROM {$tableName} WHERE dog1Id = :dog OR dog2Id = :dog",
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
                "INSERT INTO {$tableName} (dog1Id, dog2Id, covariance) VALUES " . implode(', ', $placeholders),
                $values,
                $types
            );
    }
}
