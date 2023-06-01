<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

class EventCalendar extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    //@todo this should be chainable like the buttons
    public function __invoke($performer = null)
    {
        $values = array();
        if($performer) {
            $values['performer'] = (int) $performer;
        }

        return $this->getServiceLocator()
                    ->getServiceLocator()
                    ->get('ZfcTwigRenderer')
                    ->render('event_calendar', $values);

    }

}