<?php

namespace AcePedigree\Service;

use AcePedigree\Entity\Dog;

class PedigreeAnalysis
{
    /**
     * @var int
     */
    protected $dogId;

    /**
     * @var int
     */
    protected $maxGen;

    /**
     * @var array
     */
    protected $ancestorTable = [];

    /**
     * @var array
     */
    protected $genCounts = [];

    /**
     * @param Dog $dog
     * @param int $maxGen
     */
    public function __construct(Dog $dog, $maxGen = 3)
    {
        $this->dogId = $dog->getId() ?: 0;
        $this->maxGen = $maxGen;
        $this->buildAncestorTable($dog);
        $this->calculateStatistics();
    }

    /**
     * @return Dog
     */
    public function getDog()
    {
        return $this->ancestorTable[$this->dogId]['entity'];
    }

    /**
     * @return int
     */
    public function getMaxGen()
    {
        return $this->maxGen;
    }

    /**
     * @return int
     */
    public function getCompleteGens()
    {
        $completeGens = 0;
        foreach ($this->genCounts as $gen => $count) {
            if ($count != 1 << $gen) {
                break;
            }
            $completeGens++;
        }

        return $completeGens;
    }

    /**
     * @return float
     */
    public function getCoefficientOfInbreeding()
    {
        return $this->ancestorTable[$this->dogId]['coi'];
    }

    /**
     * @return float
     */
    public function getRelationshipCoefficient()
    {
        $sireId = $this->ancestorTable[$this->dogId]['sire'];
        $damId  = $this->ancestorTable[$this->dogId]['dam'];

        if (!$sireId || !$damId) {
            return 0;
        }

        return 2 * $this->ancestorTable[$this->dogId]['coi'] / sqrt((1 + $this->ancestorTable[$sireId]['coi']) * (1 + $this->ancestorTable[$damId]['coi']));
    }

    /**
     * @return float
     */
    public function getAncestorLoss()
    {
        if (count($this->genCounts) == 1) {
            return 1;
        }

        return (count($this->ancestorTable) - 1) / (array_sum($this->genCounts) - 1);
    }

    /**
     * @return array
     */
    public function getAncestorsByBlood()
    {
        $ancestors = array_filter($this->ancestorTable, function ($row) {
            return max($row['gens']) > 0 && $row['blood'] > 0;
        });

        uasort($ancestors, function ($a, $b) {
            return ($b['blood'] > $a['blood'] ? 1 : ($b['blood'] < $a['blood'] ? -1 : max($a['gens']) - max($b['gens'])));
        });

        return $ancestors;
    }

    /**
     * @param Dog $dog
     * @param int $gen
     */
    private function buildAncestorTable(Dog $dog = null, $gen = 0, $line = null, $path = [])
    {
        if ($dog) {
            if ($gen > 0) {
                if (!isset($this->genCounts[$gen])) {
                    $this->genCounts[$gen] = 0;
                }
                $this->genCounts[$gen]++;
            }

            $dogId = $dog->getId() ?: 0;

            if (!isset($this->ancestorTable[$dogId])) {
                $this->ancestorTable[$dogId] = [
                    'entity' => $dog,
                    'sire'   => ($gen < $this->maxGen && $dog->getSire() ? $dog->getSire()->getId() : null),
                    'dam'    => ($gen < $this->maxGen && $dog->getDam() ? $dog->getDam()->getId() : null),
                    'cov'    => [],
                    'coi'    => 0,
                    'gens'   => [],
                    'paths'  => [],
                    'blood'  => 0,
                    'ic'     => 0,
                ];
            }

            $this->ancestorTable[$dogId]['gens'][] = $gen;
            $this->ancestorTable[$dogId]['blood'] += 1 / (1 << $gen);

            if ($line) {
                $path[] = $dogId;
                $this->ancestorTable[$dogId]['paths'][$line][] = $path;
            }

            if ($gen < $this->maxGen) {
                $this->buildAncestorTable($dog->getSire(), $gen + 1, $line ?: 'sire', $path);
                $this->buildAncestorTable($dog->getDam(),  $gen + 1, $line ?: 'dam',  $path);
            }
        }
    }

    /**
     * @return void
     */
    private function calculateStatistics()
    {
        uasort($this->ancestorTable, function ($a, $b) {
            return max($b['gens']) - max($a['gens']);
        });

        $ancestorIds = array_keys($this->ancestorTable);

        for ($i = 0; $i < count($ancestorIds); $i++) {
            for ($j = $i; $j < count($ancestorIds); $j++) {
                $this->calculateCovariance($ancestorIds[$i], $ancestorIds[$j]);
            }
        }

        for ($i = 0; $i < count($ancestorIds); $i++) {
            $this->calculateContribution($ancestorIds[$i]);
        }
    }

    /**
     * @param int $dog1Id
     * @param int $dog2Id
     * @return float
     */
    private function calculateCovariance($dog1Id, $dog2Id)
    {
        if (!isset($dog1Id) || !isset($dog2Id)) {
            return 0;
        } elseif (max($this->ancestorTable[$dog1Id]['gens']) < max($this->ancestorTable[$dog2Id]['gens'])) {
            list($dog1Id, $dog2Id) = array($dog2Id, $dog1Id);
        }

        $cov = &$this->ancestorTable[$dog1Id]['cov'][$dog2Id];

        if (!isset($cov)) {
            $sireId = $this->ancestorTable[$dog2Id]['sire'];
            $damId  = $this->ancestorTable[$dog2Id]['dam'];

            if ($dog1Id == $dog2Id) {
                $cov = 1 + ($this->calculateCovariance($sireId, $damId) / 2);
                $this->ancestorTable[$dog1Id]['coi'] = $cov - 1;
            } else {
                $cov = ($this->calculateCovariance($dog1Id, $sireId) + $this->calculateCovariance($dog1Id, $damId)) / 2;
            }

            $this->ancestorTable[$dog2Id]['cov'][$dog1Id] = $cov;
        }

        return $cov;
    }

    /**
     * @param int $dogId
     * @return float
     */
    private function calculateContribution($dogId)
    {
        if (!isset($this->ancestorTable[$dogId]['paths']['sire']) || !isset($this->ancestorTable[$dogId]['paths']['dam'])) {
            return 0;
        }

        $ic = &$this->ancestorTable[$dogId]['ic'];

        foreach ($this->ancestorTable[$dogId]['paths']['sire'] as $sirePath) {
            foreach ($this->ancestorTable[$dogId]['paths']['dam'] as $damPath) {
                if (count(array_intersect($sirePath, $damPath)) == 1) {
                    $ic += pow(0.5, count($sirePath) + count($damPath) - 1) * (1 + $this->ancestorTable[$dogId]['coi']);
                }
            }
        }

        return $ic;
    }
}
