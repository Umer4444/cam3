<?php

namespace Application\Controller;

use Doctrine\Common\Collections\Criteria;
use PerfectWeb\Core\Traits;
use Zend\Mvc\Controller\AbstractConsoleController;

/**
 * Class CronController
 * @package Application\Controller
 */
class CronController extends AbstractConsoleController
{

    use Traits\EntityManager;

    function scheduleAlertAction()
    {

        $twilio = $this->getServiceLocator()->get('twilio');

        // @todo make sure this works for all scheldule types
        $match = Criteria::create()->where(Criteria::expr()->eq('startHour', date('G', strtotime('-1 hour'))))
                                   /*->andWhere(Criteria::expr()->lt('toDate', new \DateTime()))*/;
        $notificationScheldule = $this->getEntityManager()
                                      ->getRepository(\Application\Entity\ModelSchedule::class)
                                      ->matching($match);
        $sms = 'Your show is about to start in 1h !';

        /** @var \Application\Entity\ModelSchedule $schedule */
        foreach ($notificationScheldule as $schedule) {
            $twilio->account->sms_messages->create('camclients', '0040721499171'/*$schedule->getUser()->getPhone()*/,
                                                   $sms);
            $output .= $schedule->getUser()->getPhone() . ' -- ';
            break;
        }

        return $output;

    }

}