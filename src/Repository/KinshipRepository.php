<?php

namespace AcePedigree\Repository;

use AcePedigree\Entity\Kinship;
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
}
