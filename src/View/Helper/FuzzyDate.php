<?php

namespace AcePedigree\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FuzzyDate extends AbstractHelper
{
    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @return string
     */
    public function __invoke($year = null, $month = null, $day = null)
    {
        $date = (new \DateTime())->setDate($year ?? 1, $month ?? 1, $day ?? 1);
        return $date->format(($year ? ($month ? ($day ? 'j M Y' : 'M Y') : 'Y') : ''));
    }
}
