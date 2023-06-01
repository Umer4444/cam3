<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use PerfectWeb\Core\Traits;
use Doctrine\Common\Collections\Criteria;

class NextBlog extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use Traits\EntityManager;

    public function __invoke($blog, $next = null)
    {

        $createCriteria = Criteria::create();
        $exp = Criteria::expr();

        $match = $createCriteria->where($next ? $exp->lt('id', $blog->getId()) : $exp->gt('id', $blog->getId()))
                                ->setMaxResults(1);

        $post = $this->getEntityManager()->getRepository('Application\Entity\BlogPosts')->matching($match)->first();

        if (!$post) {
            return false;
        }

        // @todo replace with route
        $url = '/blog/'.$post->getSlug();

        return $url;
    }

}


