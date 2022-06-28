<?php

namespace AcePedigree\Entity\DTO;

use AcePedigree\Entity\Animal;

class AnimalDTO
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var AnimalDTO
     */
    protected $sire;

    /**
     * @var AnimalDTO
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
     * @var Animal
     */
    protected $entity;

    public function __construct(Animal $animal)
    {
        $this->id = $animal->getId();
        $this->entity = $animal;

        if ($animal->getSire()) {
            $this->sire = $this->addParent($animal->getSire(), 'sire');
        }

        if ($animal->getDam()) {
            $this->dam = $this->addParent($animal->getDam(), 'dam');
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
     * @return AnimalDTO
     */
    public function getSire()
    {
        return $this->sire;
    }

    /**
     * @return AnimalDTO
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
     * @return Animal
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return float
     */
    public function getInbreedingCoefficient()
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

        return 2 * $this->getInbreedingCoefficient() /
            sqrt((1 + $this->sire->getInbreedingCoefficient()) * (1 + $this->dam->getInbreedingCoefficient()));
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
        return 0.5 * (
            ($this->getSire() ? $this->getSire()->getEntity()->getAverageCovariance() : 0) +
            ($this->getDam()  ? $this->getDam()->getEntity()->getAverageCovariance()  : 0)
        );
    }

    /**
     * @param AnimalDTO $dto
     * @return float
     */
    public function getConsanguinityWith(AnimalDTO $dto)
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
     * @param AnimalDTO $dto
     * @return float
     */
    public function getCovarianceWith(AnimalDTO $dto)
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
     * @param AnimalDTO $dto
     * @return bool
     */
    public function isDescendantOf(AnimalDTO $dto)
    {
        if ($dto == $this) {
            return true;
        }

        return ($this->sire && $this->sire->isDescendantOf($dto)) || ($this->dam && $this->dam->isDescendantOf($dto));
    }

    /**
     * @param AnimalDTO $dto
     * @return float
     */
    public function getInbreedingContributionFrom(AnimalDTO $dto)
    {
        if (!isset($this->ancestorPaths[$dto->getId()]['sire']) || !isset($this->ancestorPaths[$dto->getId()]['dam'])) {
            return 0;
        }

        $contribution = 0;

        foreach ($this->ancestorPaths[$dto->getId()]['sire'] as $sirePath) {
            foreach ($this->ancestorPaths[$dto->getId()]['dam'] as $damPath) {
                if (count(array_intersect($sirePath, $damPath)) == 1) {
                    $contribution += pow(0.5, count($sirePath) + count($damPath) - 1) * (1 + $dto->getInbreedingCoefficient());
                }
            }
        }

        return $contribution;
    }

    /**
     * @return array
     */
    public function getGenerationTotalsFor(AnimalDTO $dto)
    {
        if (!isset($this->ancestorPaths[$dto->getId()])) {
            return [];
        }

        $generationTotals = [];
        $paths = call_user_func_array('array_merge', array_values($this->ancestorPaths[$dto->getId()]));

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
    public function getShortestPathTo(AnimalDTO $dto)
    {
        return min(array_keys($this->getGenerationTotalsFor($dto)));
    }

    /**
     * @param Animal $animal
     * @param string $line
     * @return AnimalDTO
     */
    private function addParent(Animal $animal, $line)
    {
        $parent = $animal->getDTO();

        $this->totalAncestors += 1 + $parent->getTotalAncestors();

        $this->uniqueAncestors[$parent->getId()] = $parent;
        $this->uniqueAncestors += $parent->getUniqueAncestors();

        $this->ancestorPaths[$parent->getId()][$line][] = [$parent->getId()];

        foreach ($parent->getAncestorPaths() as $ancestorId => $paths) {
            $paths = call_user_func_array('array_merge', array_values($paths));
            array_walk($paths, function (&$path) use ($parent) {
                array_unshift($path, $parent->getId());
            });
            $this->ancestorPaths[$ancestorId][$line] = $paths;
        }

        return $parent;
    }
}