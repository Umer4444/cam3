<?php
namespace UserProfile\Controller;

use UserAccount\Controller\UserAccountController as Uac;
use PerfectWeb\Core\Traits;
use Zend\View\Model\ViewModel;

/**
 * Class UserController
 * @package UserProfile\Controller
 */
class UserController extends Uac
{
    use Traits\EntityManager;

    public function editProfileAction()
    {
        return $this->edit();
    }

    public function editProfileProcessAction()
    {
        return $this->process();
    }

    public function profileAction()
    {}

    public function timelineAction()
    {}

    public function blogAction()
    {}

    public function videosAction()
    {}

    public function wallAction()
    {}

}

