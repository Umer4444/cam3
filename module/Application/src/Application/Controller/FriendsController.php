<?php

namespace Application\Controller;

use Nicovogelaar\CrudController\Mvc\Controller\AbstractCrudController;
use Zend\View\Model\ViewModel;
use Doctrine\Common\Collections\Criteria;

/**
 * Class FriendsController
 * @package Application\Controller
 */
class FriendsController extends AbstractCrudController
{

    public function __construct()
    {

    }

    public function listAction()
    {

        $params = $this->params()->fromRoute();

        $criteria = Criteria::create()->where(Criteria::expr()->eq("status", $params["status"]));
        $criteria->orderBy(['position' => 'asc']);
        $friends = $this->zfcUserAuthentication()->getIdentity()->getFriends()->matching($criteria);

        return array(
            'friends' => $friends,
        );

    }

}