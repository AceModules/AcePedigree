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
     * @param Dog $dog
     * @param int $maxGen
     */
    public function __construct(Dog $dog, $maxGen = 3)
    {
        $this->dogId = $dog->getId() ?: 0;
        $this->maxGen = $maxGen;
        $this->buildAncestorTable($dog, $maxGen);
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
     * @return float
     */
    public function getCoefficientOfInbreeding()
    {
        return $this->ancestorTable[$this->dogId]['coi'];
    }

    /**
     * @return array
     */
    public function getAncestorsByContribution()
    {
        $ancestors = array_filter($this->ancestorTable, function ($row) {
            return $row['ic'] > 0;
        });

        uasort($ancestors, function ($a, $b) {
            return ($b['ic'] > $a['ic'] ? 1 : ($b['ic'] < $a['ic'] ? -1 : 0));
        });

        return $ancestors;
    }

    /**
     * @return array
     */
    public function getAncestorsByBlood()
    {
        $ancestors = array_filter($this->ancestorTable, function ($row) {
            return $row['blood'] > 0;
        });

        uasort($ancestors, function ($a, $b) {
            return ($b['blood'] > $a['blood'] ? 1 : ($b['blood'] < $a['blood'] ? -1 : 0));
        });

        return $ancestors;
    }

    /**
     * @param Dog $dog
     * @param int $maxGen
     * @param int $gen
     */
    private function buildAncestorTable(Dog $dog = null, $maxGen, $gen = 0, $line = null, $path = [])
    {
        if ($dog) {
            $dogId = $dog->getId() ?: 0;
            if (!isset($this->ancestorTable[$dogId])) {
                $this->ancestorTable[$dogId] = [
                    'entity' => $dog,
                    'sire'   => ($gen < $maxGen && $dog->getSire() ? $dog->getSire()->getId() : null),
                    'dam'    => ($gen < $maxGen && $dog->getDam() ? $dog->getDam()->getId() : null),
                    'max'    => 0,
                    'cov'    => [],
                    'coi'    => 0,
                    'paths'  => [],
                    'blood'  => 0,
                    'ic'     => 0,
                ];
            }

            $this->ancestorTable[$dogId]['max'] = max($gen, $this->ancestorTable[$dogId]['max']);
            $this->ancestorTable[$dogId]['blood'] += 1 / (1 << $gen);

            if ($line) {
                $path[] = $dogId;
                $this->ancestorTable[$dogId]['paths'][$line][] = $path;
            }

            if ($gen < $maxGen) {
                $this->buildAncestorTable($dog->getSire(), $maxGen, $gen + 1, $line ?: 'sire', $path);
                $this->buildAncestorTable($dog->getDam(),  $maxGen, $gen + 1, $line ?: 'dam',  $path);
            }
        }
    }

    /**
     * @return void
     */
    private function calculateStatistics()
    {
        uasort($this->ancestorTable, function ($a, $b) {
            return $b['max'] - $a['max'];
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
        } elseif ($this->ancestorTable[$dog1Id]['max'] < $this->ancestorTable[$dog2Id]['max']) {
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
