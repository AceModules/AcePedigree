<?php

namespace AcePedigree\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class OfaLink extends AbstractHelper
{
    /**
     * @param int $ofaNumber
     * @return string
     */
    public function __invoke($ofaNumber = null)
    {
        if ($ofaNumber) {
            return '<a href="https://www.ofa.org/advanced-search?f=sr&appnum=' . $ofaNumber . '" target="_blank">View test results</a>';
        }
    }
}
