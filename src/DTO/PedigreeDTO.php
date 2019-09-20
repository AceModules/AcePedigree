<?php

namespace AcePedigree\DTO;

use AcePedigree\Entity\Dog;

class PedigreeDTO
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var PedigreeDTO
     */
    protected $sire;

    /**
     * @var PedigreeDTO
     */
    protected $dam;

    /**
     * @var int
     */
    protected $totalAncestors = 0;

    /**
     * @var array
     */
    protected $uniqueAncestors = [];

    /**
     * @var array
     */
    protected $ancestorPaths = [];

    /**
     * @var int
     */
    protected $lastCompleteGen = 0;

    /**
     * @var Dog
     */
    protected $entity;

    public function __construct(Dog $dog)
    {
        $this->id = $dog->getId();
        $this->entity = $dog;

        if ($dog->getSire()) {
            $this->sire = $this->addParent($dog->getSire(), 'sire');
        }

        if ($dog->getDam()) {
            $this->dam = $this->addParent($dog->getDam(), 'dam');
        }

        if ($this->sire && $this->dam) {
            $this->lastCompleteGen = 1 + (min($this->sire->getLastCompleteGen(), $this->dam->getLastCompleteGen()));
        }

        uasort($this->uniqueAncestors, function($dtoA, $dtoB) {
            $blood = $this->getConsanguinityWith($dtoB) - $this->getConsanguinityWith($dtoA);
            return (($blood > 0) - ($blood < 0)) ?:
                ($this->getShortestPathTo($dtoA) - $this->getShortestPathTo($dtoB)) ?:
                ($dtoA->getEntity()->getSex() - $dtoB->getEntity()->getSex()) ?:
                strcmp($dtoA->getEntity()->getName(), $dtoB->getEntity()->getName());
        });
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PedigreeDTO
     */
    public function getSire()
    {
        return $this->sire;
    }

    /**
     * @return PedigreeDTO
     */
    public function getDam()
    {
        return $this->dam;
    }

    /**
     * @return int
     */
    public function getTotalAncestors()
    {
        return $this->totalAncestors;
    }

    /**
     * @return array
     */
    public function getUniqueAncestors()
    {
        return $this->uniqueAncestors;
    }

    /**
     * @return array
     */
    public function getAncestorPaths()
    {
        return $this->ancestorPaths;
    }

    /**
     * @return int
     */
    public function getLastCompleteGen()
    {
        return $this->lastCompleteGen;
    }

    /**
     * @return Dog
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return float
     */
    public function getCoefficientOfInbreeding()
    {
        return $this->getCovarianceWith($this) - 1;
    }

    /**
     * @return float
     */
    public function getCoefficientOfRelationship()
    {
        if (!$this->sire || !$this->dam) {
            return 0;
        }

        return 2 * $this->getCoefficientOfInbreeding() /
            sqrt((1 + $this->sire->getCoefficientOfInbreeding()) * (1 + $this->dam->getCoefficientOfInbreeding()));
    }

    /**
     * @return float
     */
    public function getAncestorLoss()
    {
        if (!$this->sire && !$this->dam) {
            return 1;
        }

        return count($this->uniqueAncestors) / $this->totalAncestors;
    }

    /**
     * @return float
     */
    public function getAverageCovariance()
    {
        if (!$this->entity->getStatistics()) {
            return 0.5 * (
                ($this->sire ? $this->sire->getAverageCovariance() : 0) +
                ($this->dam ? $this->dam->getAverageCovariance() : 0)
            );
        }

        return $this->entity->getStatistics()->getAverageCovariance();
    }

    /**
     * @param PedigreeDTO $dto
     * @return float
     */
    public function getConsanguinityWith(PedigreeDTO $dto)
    {
        if ($dto == $this) {
            return 1;
        }

        list($ancestor, $descendant) = [$dto, $this];
        if ($ancestor->isDescendantOf($descendant)) {
            return 0;
        }

        return 0.5 * (
            ($descendant->getSire() ? $descendant->getSire()->getConsanguinityWith($ancestor) : 0) +
            ($descendant->getDam()  ? $descendant->getDam()->getConsanguinityWith($ancestor)  : 0)
        );
    }

    /**
     * @param PedigreeDTO $dto
     * @return float
     */
    public function getCovarianceWith(PedigreeDTO $dto)
    {
        if ($dto == $this) {
            return 1 + (0.5 *
                ($this->sire && $this->dam ? $this->sire->getCovarianceWith($this->dam) : 0)
            );
        }

        list($ancestor, $descendant) = [$dto, $this];
        if ($ancestor->isDescendantOf($descendant)) {
            list($ancestor, $descendant) = [$descendant, $ancestor];
        }

        return 0.5 * (
            ($descendant->getSire() ? $descendant->getSire()->getCovarianceWith($ancestor) : 0) +
            ($descendant->getDam()  ? $descendant->getDam()->getCovarianceWith($ancestor)  : 0)
        );
    }

    /**
     * @param PedigreeDTO $dto
     * @return bool
     */
    public function isDescendantOf(PedigreeDTO $dto)
    {
        if ($dto == $this) {
            return true;
        }

        return ($this->sire && $this->sire->isDescendantOf($dto)) || ($this->dam && $this->dam->isDescendantOf($dto));
    }

    /**
     * @param PedigreeDTO $dto
     * @return float
     */
    public function getInbreedingContributionFrom(PedigreeDTO $dto)
    {
        if (!isset($this->ancestorPaths[$dto->getId()]['sire']) || !isset($this->ancestorPaths[$dto->getId()]['dam'])) {
            return 0;
        }

        $contribution = 0;

        foreach ($this->ancestorPaths[$dto->getId()]['sire'] as $sirePath) {
            foreach ($this->ancestorPaths[$dto->getId()]['dam'] as $damPath) {
                if (count(array_intersect($sirePath, $damPath)) == 1) {
                    $contribution += pow(0.5, count($sirePath) + count($damPath) - 1) * (1 + $dto->getCoefficientOfInbreeding());
                }
            }
        }

        return $contribution;
    }

    /**
     * @return array
     */
    public function getGenerationTotalsFor(PedigreeDTO $dto)
    {
        if (!isset($this->ancestorPaths[$dto->getId()])) {
            return [];
        }

        $generationTotals = [];
        $paths = call_user_func_array('array_merge', $this->ancestorPaths[$dto->getId()]);

        array_walk($paths, function($path) use (&$generationTotals) {
            $generation = count($path);
            $generationTotals[$generation] = ($generationTotals[$generation] ?? 0) + 1;
        });

        ksort($generationTotals);

        return $generationTotals;
    }

    /**
     * @return int
     */
    public function getShortestPathTo(PedigreeDTO $dto)
    {
        return min(array_keys($this->getGenerationTotalsFor($dto)));
    }

    /**
     * @param Dog $dog
     * @param string $line
     * @return PedigreeDTO
     */
    private function addParent(Dog $dog, $line)
    {
        $parent = $dog->getDTO();

        $this->totalAncestors += 1 + $parent->getTotalAncestors();

        $this->uniqueAncestors[$parent->getId()] = $parent;
        $this->uniqueAncestors += $parent->getUniqueAncestors();

        $this->ancestorPaths[$parent->getId()][$line][] = [$parent->getId()];

        foreach ($parent->getAncestorPaths() as $ancestorId => $paths) {
            $paths = call_user_func_array('array_merge', $paths);
            array_walk($paths, function (&$path) use ($parent) {
                array_unshift($path, $parent->getId());
            });
            $this->ancestorPaths[$ancestorId][$line] = $paths;
        }

        return $parent;
    }
}