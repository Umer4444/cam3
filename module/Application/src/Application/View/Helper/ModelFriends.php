<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Doctrine\Common\Collections\Criteria;

class ModelFriends extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    public function __invoke($modelId = null)
    {

        $friendsAll = $friendsPerformers = $friends = array();
        $criteriaApproved = Criteria::create()->where(Criteria::expr()->eq("status", 1));

        if(is_null($modelId)) {
            $friendsAll = $this->view->user()->getUser()->getFriends()->matching($criteriaApproved);
        }
        else {

            $performer = $this->getServiceLocator()
                              ->getServiceLocator()
                              ->get('doctrine.entity_manager.orm_default')
                              ->getRepository('Application\Entity\User')
                              ->find($modelId);
            if ($performer) {
                $friendsAll = $performer->getFriends()->matching($criteriaApproved);
            }

        }

        foreach ($friendsAll as $friend) {

            if ($friend->getUser()->getRoles()[0]->getRoleId() == 'performer') {
                $friendsPerformers[$friend->getPosition()] = $friend;

            }
            else {
                $friends[$friend->getPosition()] = $friend;
            }
        }

        ksort($friendsPerformers);
        ksort($friends);

        return $this->getServiceLocator()->getServiceLocator()
            ->get('ZfcTwigRenderer')
            ->render('my_friends',
                array(
                    'friends' => $friends,
                    'performers' => $friendsPerformers,
                    'zf1' => 1
                )
            );

    }


}