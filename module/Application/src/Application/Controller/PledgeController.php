<?php

namespace Application\Controller;

use Solo\Model;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\Query\Expr;
use Application\Mapper\Injector;
use PerfectWeb\Core\Traits;

/**
 * Class PledgeController
 * @package Application\Controller
 */
class PledgeController extends AbstractActionController
{

    use Traits\EntityManager;

    /**
     * @return array|ViewModel
     *
     * Index Action for PledgeController. This shows a list of posts paginated IF the REQUEST is xml/http
     * else, if we got slug in the url, shows the blog post, with pagination for it!
     */
    public function indexAction()
    {

        $pledges = $this->getEntityManager()->getRepository('Application\Entity\Pledge')->findAll();

        return new ViewModel(
            array(
                'pledges' => $pledges
            )
        );

    }

    public function viewPledgeAction()
    {

        $pledges = $this->getEntityManager()->getRepository('Application\Entity\Pledge')->findAll();

        return new ViewModel(
            array(
                'pledges' => $pledges
            )
        );

    }

}