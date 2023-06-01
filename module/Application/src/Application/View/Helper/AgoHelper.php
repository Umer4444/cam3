<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

class AgoHelper extends AbstractHelper implements ServiceLocatorAwareInterface {

    use ServiceLocatorAwareTrait;

    /**
     * @param $tm
     * @param int $rcs
     * @return string
     */
    public function __invoke($tm, $rcs=0)// display "X time" ago, $rcs is precision depth
    {

        $cur_tm = time();
        $dif = $cur_tm - $tm;
        $pds = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade');
        $lngh = array(1, 60, 3600, 86400, 604800, 2630880, 31570560, 315705600);

        for ($v = count($lngh) - 1; ($v >= 0) && (($no = $dif / $lngh[$v]) <= 1); $v--) ;
        if ($v < 0)
            $v = 0;
        $_tm = $cur_tm - ($dif % $lngh[$v]);

        $no = ($rcs ? floor($no) : round($no)); // if last denomination, round

        if ($no != 1)
            $pds[$v] .= 's';
        $x = $no . ' ' . $pds[$v];

        if (($rcs > 0) && ($v >= 1))
            $x .= ' ' . $this->time_ago($_tm, $rcs - 1);

        return $x;

    }

} 