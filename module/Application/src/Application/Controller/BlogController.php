<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\Query\Expr;
use Application\Mapper\Injector;
use PerfectWeb\Core\Traits;

/**
 * Class BlogController
 * @package Application\Controller
 */
class BlogController extends AbstractActionController
{
    use Traits\EntityManager;
    use Traits\CategoryFilter;

    /**
     * @return array|ViewModel
     *
     * Index Action for BlogController. This shows a list of posts paginated IF the REQUEST is xml/http
     * else, if we got slug in the url, shows the blog post, with pagination for it!
     */
    public function indexAction()
    {

        $performer = $this->user()->getUser();
        $em = $this->getEntityManager();

        $latestVideo = $em->getRepository('Videos\Entity\Video')->findOneBy(array('userId' => $performer->getId()), array('added' =>
                'DESC'));

        $blogPostSlug = $this->params()->fromRoute('slug'); //checking if slug exists in url

        if ($blogPostSlug) { //if exists (e.g. the page is a blog view, not a list view

            if (is_numeric($blogPostSlug)) { //if instead of slug we got id from url

                $blogPostId = $blogPostSlug;

                $blogPosts = $em->getRepository('Application\Entity\BlogPosts')
                    ->findOneBy(array('id' => $blogPostId, 'status' => 1)); //then find one by id

                if ($blogPosts) {
                    $service = $this->getServiceLocator()->get('Zf2SlugGenerator\SlugService'); //get slug service
                    $goodBlogSlugNoId = $service->create($blogPosts->getTitle(), false); //slugify title
                    $goodSlug = $goodBlogSlugNoId . '-' . $blogPosts->getId(); //add id to slug

                    return $this->redirect()->toRoute('solo/blog', array('slug' => $goodSlug)); //redirect to correct page
                } else {

                    return $this->redirect()->toRoute('solo/blog');

                }
            } else { //if we got slug in url

                $blogPostId = end(explode('-', $blogPostSlug)); //get id of post
                $repository = $em->getRepository('Application\Entity\BlogPosts');
                $service = $this->getServiceLocator()->get('Zf2SlugGenerator\SlugService');
                $authorize = $this->getServiceLocator()->get('BjyAuthorize\Provider\Identity\ProviderInterface');
                $role = $authorize->getIdentityRoles()[0];

                if ($role != 'guest') $role = $role->getRoleId();

                if ($role == 'member') $role = 'members';

                $qb = $repository->createQueryBuilder('u');

                $qb->select(array('DISTINCT u', 'a.type', 'a.date', 'a.chips'))
                    ->join('Application\Entity\BlogAccess', 'a', 'WITH', 'a.post = u.id')
                    ->where("u.idModel = :user")
                    ->andWhere('a.type IN (:types)')
                    ->andWhere('u.id = :id')
                    ->setParameters(array(
                        'user' => $performer->getId(),
                        'types' => array('everyone', $role),
                        'id' => $blogPostId
                    ));

                $blogPost = $qb->getQuery()->getScalarResult()[0];

                $goodBlogSlugNoId = $service->create($blogPost['u_title'], false); //slugify its title
                $goodSlug = $goodBlogSlugNoId . '-' . $blogPost['u_id']; //add id to slug

                if ($this->zfcUserAuthentication()->hasIdentity()) {
                    $userId = $this->zfcUserAuthentication()->getIdentity()->getId();

                    $user = $em->getRepository('Application\Entity\User')
                        ->findOneBy(array('id' => $userId));
                    $chips = $user->getChips();
                }
                $amount = $blogPost['chips'];

                if ($blogPost['date'] > time()) {

                    $blogPost = array(
                        'u_title' => 'This post will be live on ' . date('m/d/Y', $blogPost['date']),
                    );
                    $buyable = null;
                    $form = null;

                } else if
                ($blogPost['date'] <= time() && $blogPost['chips'] > 0 && $this->zfcUserAuthentication()->hasIdentity()
                ) {

                    $buyable = 1;
                    $form = new \Solo\Form\PurchaseForm($blogPostId, 'blog_post');
                    $bought = $em->getRepository('Application\Entity\PurchasedContent')
                        ->getPurchasedById($userId, 'blog_post', $blogPost['u_id']);
                    if ($bought) $buyable = 0;

                } else if ($blogPost['date'] <= time() && $blogPost['chips'] == 0) {

                    $buyable = 0; //costs nothing
                    $form = null;
                } else {
                    $form = null;
                    $buyable = 0;
                }

                $qbPrev = $repository->createQueryBuilder('u');

                $qbPrev->select(array('DISTINCT u', 'a.type', 'a.date', 'a.chips'))
                    ->join('Application\Entity\BlogAccess', 'a', 'WITH', 'a.post = u.id')
                    ->where("u.idModel = :user")
                    ->andWhere('a.type IN (:types)')
                    ->andWhere('u.id < :id')
                    ->orderBy('u.id', 'DESC')
                    ->setParameters(array(
                        'user' => $performer->getId(),
                        'types' => array('everyone', $role),
                        'id' => $blogPostId
                    ));
                $prev = $qbPrev->getQuery()->getScalarResult();

                if ($prev) {

                    $prev = $prev[0];

                    $prevSlugNoId = $service->create($prev['u_title'], false);
                    $prevGoodSlug = $prevSlugNoId . '-' . $prev['u_id'];
                } else {

                    $prevGoodSlug = null;
                }

                $qbNext = $repository->createQueryBuilder('u');

                $qbNext->select(array('DISTINCT u', 'a.type', 'a.date', 'a.chips'))
                    ->join('Application\Entity\BlogAccess', 'a', 'WITH', 'a.post = u.id')
                    ->where("u.idModel = :user")
                    ->andWhere('a.type IN (:types)')
                    ->andWhere('u.id > :id')
                    ->orderBy('u.id', 'ASC')
                    ->setParameters(array(
                        'user' => $performer->getId(),
                        'types' => array('everyone', $role),
                        'id' => $blogPostId
                    ));
                $next = $qbNext->getQuery()->getScalarResult();

                if ($next) {

                    $next = $next[0];

                    $nextSlugNoId = $service->create($next['u_title'], false);
                    $nextGoodSlug = $nextSlugNoId . '-' . $next['u_id'];
                } else {

                    $nextGoodSlug = null;
                }

                $latestNews = $em->getRepository('Application\Entity\Announcements')
                    ->findBy(array('userId' => $performer->getId()), array('id' => 'DESC'), 4);

                if ($goodSlug == $blogPostSlug) { //if slug in url is correct

                    if ($blogPost['u_category']) {

                        $catId = $blogPost['u_category'];
                        $category = $em->getRepository('Application\Entity\Categories')
                            ->findOneBy(array('id' => $catId, 'entity' => 'blog')); //find it by its id

                    } else {
                        $category = null;

                    }
                    if (!isset($chips)) $chips = 0;

                    $view = new ViewModel(array(
                        'amount' => $amount,
                        'chips' => $chips,
                        'form' => $form,
                        'buyable' => $buyable,
                        'prev' => $prevGoodSlug,
                        'next' => $nextGoodSlug,
                        'video' => $latestVideo,
                        'category' => $category,
                        'news' => $latestNews,
                        'blogPost' => $blogPost,
                        'viewPost' => '1'
                    ));
                    return $view;

                } else { //if the slug in url is not correct, but the id is

                    $this->redirect()->toRoute('solo/blog', array('slug' => $goodSlug));

                } //if the id is correct

            }

        }
        else { //blog list

            $latestNews = $em->getRepository('Application\Entity\Announcements')
                ->findBy(array('userId' => $performer->getId()), array('id' => 'DESC'), 4);

            $friends = $em->getRepository('Application\Entity\User')
                            ->findBy(array('id' => $performer->getId()), array(), 6);

            $repository = $em->getRepository('Application\Entity\BlogPosts');

            $itemsPerPage = 9;
            $page = 1;

            if ($this->params()->fromRoute('page')) {
                $pageRoute = explode("-", $this->params()->fromRoute('page'), 2);
                $page = $pageRoute[1];
            }

            $count = $repository->countPosts($itemsPerPage, $performer->getId());

            $offset = $itemsPerPage * ($page - 1);
            $authorize = $this->getServiceLocator()->get('BjyAuthorize\Provider\Identity\ProviderInterface');

            $results = $repository->getPostsWithDateBuilder($performer->getId(), $authorize->getIdentityRoles()[0], $offset, $itemsPerPage);

            if ($offset > $count) {
                if ($this->getRequest()->isXmlHttpRequest()) return false;
                else return false;
                //@todo redirect to blog list page-1 where page-1 >= 1
            }
            $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\Null($count));
            $paginator->setItemCountPerPage($itemsPerPage);

            if ($page) $paginator->setCurrentPageNumber($page);

            $view = new ViewModel(array(
                    'video' => $latestVideo,
                    'posts' => $results,
                    'blogPosts' => $paginator,
                    'friends' => $friends,
                    'news' => $latestNews,
                )
            );

            $view->setTerminal($this->getRequest()->isXmlHttpRequest());
            return $view;

        }

        return new ViewModel();

    }

    public function listAction()
    {

        //$this->filterByCategory($this->params()->fromRoute('categoryId'));

        /** @var \Application\Paginator\BlogPaginator $paginator */
        $paginator = $this->getServiceLocator()->get(\Application\Paginator\BlogPaginator::class);
        $paginator->setData(array_merge($this->params()->fromRoute(), ['route' => true]));

        //$this->getEntityManager()->getFilters()->disable('category');

        return new ViewModel(
            ['paginator' => $paginator]
        );

    }

    public function viewAction()
    {
        return new ViewModel([
            'blog' => $this->getEntityManager()
                           ->getRepository(\Application\Entity\BlogPosts::class)
                           ->findOneBySlug($this->params()
                           ->fromRoute(Injector::BLOG_SLUG))
        ]);
    }

}