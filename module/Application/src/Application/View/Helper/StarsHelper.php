<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

/**
 * Class StarsHelper
 * @package Application\View\Helper
 */
class StarsHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * @param bool $type
     * @param bool $id
     * @param int $average
     * @param string $class
     * @param string $starSize
     * @return bool
     */
    public function __invoke($type = false, $id = false, $average = 0, $class = '', $starSize = 'big')
    {
        if (!$type || !$id) return false;
        if (isset($_SESSION["rate"][$type]) && in_array($id, $_SESSION["rate"][$type])) {
            $class .= ' jDisabled';
        }

        return $this->getServiceLocator()->getServiceLocator()
            ->get('ZfcTwigRenderer')
            ->render('stars', array(
                'type' => $type,
                'id' => $id,
                'average' => $average,
                'class' => $class,
                'starSize' => $starSize
            ));

    }

}