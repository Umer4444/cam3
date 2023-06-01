<?php

namespace Solo\Controller;

use Solo\Form;
use Solo\Model;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Doctrine\Common\Collections\Criteria;
use PerfectWeb\Core\Traits;
use PerfectWeb\Payment\Entity\PurchasedContent;

/**
 * Class IndexController
 * @package Solo\Controller
 *
 * This is the indexController for solo, it contains all the actions, except for login an blog
 */
class IndexController extends AbstractActionController
{
    use Traits\EntityManager;

    /**
     * @return array|ViewModel
     * This si the index Action, the initial action for homepage
     */
    public function indexAction()
    {
        $prices = $this->getServiceLocator()->get('em')
            ->getRepository('PerfectWeb\Core\Entity\ResourceValue')
            ->findOneBy(array('user' => $this->user()->getUser()->getId()));

        $view = new ViewModel(array(
            'prices' => $prices
        ));

        return $view;

    }

    /**
     * @return ViewModel
     */
    public function guestbookAction()
    {

        $sm = $this->getServiceLocator();

        $modelId = user()->getUser()->getId();

        $modelRepo = $this->getEntityManager()->getRepository('Application\Entity\Model');
        $model = $modelRepo->findOneBy(array('user' => $modelId));
        $guestbook = $model->getGuestbook();

        return new ViewModel(array('guestbook' => $guestbook, 'modelId' => $modelId));
    }

    /**
     * @return ViewModel
     */
    public function socialAction()
    {}

    /**
     * @return ViewModel
     * About Page action
     */
    public function aboutAction()
    {
        $sm = $this->getServiceLocator();
        $entityManager = $this->getEntityManager();
        $userRepo = $entityManager->getRepository('Application\Entity\User');
        $isFriend = 0;
        $followers = null;

        $performerId = $this->user()->getUser();
        $form = $sm->get('Application\Service\ReviewFormService')->reviewForm();
        $videoRepo = $entityManager->getRepository(\Videos\Entity\Video::class);

        if ($this->zfcUserAuthentication()->hasIdentity()) {

            $follower = $this->zfcUserAuthentication()->getIdentity();
            $friendsRepo = $entityManager->getRepository('Application\Entity\Friends');
            $isFriend = $friendsRepo->isFriend($follower, $performerId);
            $friends = $friendsRepo->findOneBy(array('user' => $performerId));
            $followers = $entityManager
                ->getRepository('Application\Entity\Followers')
                ->findOneBy(['followed' => $follower->getId(), 'followers' => $performerId]);

        }

        $topTippers = $entityManager->getRepository('Application\Entity\TransferWall')
            ->getTopTippers($performerId);

        $topContributers = $entityManager->getRepository('Application\Entity\PledgeFunder')
            ->findByPledge($performerId);

        $profileSettings = $userRepo
            ->getProfileSettings($performerId);

        //set value in country resource
        $profileAddress = $profileSettings['default_profile_address']['value'];

        if (method_exists($profileAddress, 'getCountry')) {
            $country = $profileSettings["default_profile_address"]["value"]->getCountry();
            if (method_exists($country, 'getCountry')) {

                $profileSettings["country"]["value"] = $country->getCountry();
            } else {

                $profileSettings["country"]["value"] = 'Unknown';
            }

        } else {

            $profileSettings["country"]["value"] = 'Unknown';

        }

        $profile = $userRepo
            ->getPerformerProfile($performerId);

        $performer = $userRepo->findOneBy(array('id' => $performerId));

        $role = $performer->getRoles()[0]->getRoleId();

        //if (is_numeric($profileSettings['bio_video']['value'])) {


        if (is_array($performer->getIntroVideo()) && array_key_exists('value',$performer->getIntroVideo())) {
            //$bioVideo = $videoRepo->findOneBy(array('id' => $profileSettings['bio_video']['value']));
            $bioVideo = $performer->getIntroVideo();
        } else {
            $bioVideo = null;
        }

        if (is_numeric($profileSettings['turns_on_video']['value'])) {

            $turnOnVideo = $videoRepo->findOneBy(array('id' => $profileSettings['turns_on_video']['value']));
        } else {
            $turnOnVideo = null;
        }

        if (is_numeric($profileSettings['turns_off_video']['value'])) {

            $turnOffVideo = $videoRepo->findOneBy(array('id' => $profileSettings['turns_off_video']['value']));
        } else {
            $turnOffVideo = null;
        }

        if (is_numeric($profileSettings['private_do_video']['value'])) {

            $privateDoVideo = $videoRepo->findOneBy(array('id' => $profileSettings['private_do_video']['value']));
        } else {
            $privateDoVideo = null;
        }

        if (is_numeric($profileSettings['private_dont_video']['value'])) {

            $privateDontVideo = $videoRepo->findOneBy(array('id' => $profileSettings['private_dont_video']['value']));
        } else {
            $privateDontVideo = null;
        }

        if (is_numeric($profileSettings['interests_hobbies_video']['value'])) {

            $interestsVideo = $videoRepo->findOneBy(array('id' => $profileSettings['interests_hobbies_video	']['value']));
        } else {
            $interestsVideo = null;
        }

        if (is_numeric($profileSettings['room_rules_video']['value'])) {

            $roomVideo = $videoRepo->findOneBy(array('id' => $profileSettings['room_rules_video']['value']));
        } else {
            $roomVideo = null;
        }

        return new ViewModel(array(
            'bioVideo' => $bioVideo,
            'turnOnVideo' => $turnOnVideo,
            'turnOffVideo' => $turnOffVideo,
            'privateDoVideo' => $privateDoVideo,
            'privateDontVideo' => $privateDontVideo,
            'roomVideo' => $roomVideo,
            'interestsVideo' => $interestsVideo,
            'isFriend' => $isFriend,
            'friends' => $friends,
            'role' => $role,
            'performer' => $performer,
            'performerId' => $performerId,
            'followers' => $followers,
            'topContributers' => $topContributers,
            'topTippers' => $topTippers,
            'profile' => $profile,
            'form' => $form,
            //'video' => $latestVideo,
            'userProfile' => $profileSettings,
        ));
    }

    /**
     * @return ViewModel
     * Action for videos, here we take the slug from url, take out the id for the slug, query the database
     * and return a list of videos or a video
     */
    public function videosAction()
    {
        $sm = $this->getServiceLocator();
        $em = $this->getEntityManager();
        $videoSlug = $this->params()->fromRoute('videoSlug'); //checking if slug exists in url

        if ($videoSlug) { //if exists (e.g. you are in a video view, not a list view

            if (is_numeric($videoSlug)) { //if instead of slug we got id from url

                $videoId = $videoSlug;
                $video = $em->getRepository('Videos\Entity\Video')
                    ->findOneBy(array('id' => $videoId));

                if ($video) {
                    $service = $this->getServiceLocator()->get('Zf2SlugGenerator\SlugService'); //get slug service
                    $goodSlugNoId = $service->create($video->getTitle(), false); //slugify title
                    $goodSlug = $goodSlugNoId . '-' . $video->getId(); //add id to slug

                    return $this->redirect()->toRoute('solo/videos', array('videoSlug' => $goodSlug));
                    //redirect to correct page
                } else {

                    return $this->redirect()->toRoute('solo/videos');

                }
            } else { //if we got slug in url

                $videoId = end(explode('-', $videoSlug)); //get id of post

                $video = $em->getRepository('Videos\Entity\Video')
                    ->findOneBy(array('id' => $videoId));

                if ($video) {

                    $service = $this->getServiceLocator()->get('Zf2SlugGenerator\SlugService');
                    $goodSlugNoId = $service->create($video->getTitle(), false); //slugify its title
                    $goodSlug = $goodSlugNoId . '-' . $video->getId(); //add id to slug

                    //generate thumbs
                    $fileInfo = pathinfo($video->getCover());
                    for ($i = 1; $i <= 6; $i++) {
                        $thumbs[] = "/uploads/videos/" . $fileInfo["dirname"] . "/" . substr($fileInfo["filename"], 0, -1) . $i . '.' . $fileInfo["extension"];

                    }

                    if ($goodSlug == $videoSlug) { //if slug in url is correct

                        $reviews = $em
                            ->getRepository('Application\Entity\Reviews')
                            ->findBy(array('resourceId' => $videoId, 'resourceType' => 'video', 'active' => 1),
                                array('date' => 'DESC'));
                        if ($this->zfcUserAuthentication()->hasIdentity()) {

                            $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
                            $reviewsPending = $em
                                ->getRepository('Application\Entity\Reviews')
                                ->findBy(array('resourceId' => $videoId, 'resourceType' => 'video', 'active' => 0, 'user' => $userId), array('date' => 'DESC'));

                            if (!$reviewsPending) {

                                $reviewsPending = null;

                            }

                        } else {

                            $reviewsPending = null;
                        }

                        $perSecond = $em->getRepository('Application\Entity\Config')
                            ->findOneBy(array('var' => 'tokens_per_second'))->getVal();
                        if ($this->zfcUserAuthentication()->hasIdentity()) {

                            $id = $this->zfcUserAuthentication()->getIdentity()->getId();
                            $bought = $em->getRepository('Application\Entity\PurchasedContent')
                                ->getPurchasedById($id, 'video', $videoId);

                            if ($bought) {
                                $videoRepo = $video = $em
                                    ->getRepository('Videos\Entity\Video');
                                $video = $videoRepo->findOneBy(array('id' => $videoId));
                                $categories = $videoRepo->getCategories($videoId);
                                $purchased = 1;

                            } else {

                                $video = array();
                                $videoRepo = $em->getRepository('Videos\Entity\Video');

                                $videoObj = $videoRepo->findOneBy(array('id' => $videoId));

                                //~r($videoObj);
                                $video['duration'] = $videoObj->getDuration();
                                $video['title'] = $videoObj->getTitle();
                                $video['cover'] = $videoObj->getCover();
                                $categories = $videoRepo->getCategories($videoId);
                                $purchased = 0;
                                $price = $perSecond * $video['duration'];
                                $userChips = $em->getRepository('Application\Entity\User')
                                    ->findOneBy(array('id' => $id))->getChips();

                                if ($userChips >= $price) {

                                    $enoughChips = 1;
                                } else {
                                    $enoughChips = 0;
                                }
                                $buyForm = new \Solo\Form\PurchaseForm($videoId, 'video');

                            }

                        } else {

                            $video = array();
                            $videoRepo = $em->getRepository('Videos\Entity\Video');

                            $videoObj = $videoRepo->findOneBy(array('id' => $videoId));
                            $video['duration'] = $videoObj->getDuration();
                            $video['title'] = $videoObj->getTitle();
                            $videoId = $videoObj->getId();
                            $purchased = 0;
                            $video['cover'] = $videoObj->getCover();
                            $categories = $videoRepo->getCategories($videoId);

                        }

                        $form = $sm->get('Application\Service\ReviewFormService')->reviewForm();
                        // $related = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')
                        //   ->getRepository('Application\Entity\Video')->findBy(array(), array(), 6);
                        if (!isset($enoughChips)) $enoughChips = 0;
                        if (!isset($buyForm)) $buyForm = null;
                        if (!isset($userChips)) $userChips = null;
                        if (empty($categories)) $categories = '';
                        if (empty($thumbs)) $thumbs = null;

                        return new ViewModel(array(
                            'thumbs' => $thumbs,
                            'categories' => $categories,
                            'userChips' => $userChips,
                            'buyForm' => $buyForm,
                            'enoughChips' => $enoughChips,
                            'perSecond' => $perSecond,
                            'purchased' => $purchased,
                            'reviewsPending' => $reviews,
                            'reviews' => $reviews,
                            'form' => $form,
                            'video' => $video,
                            'videoId' => $videoId,
                            'name' => $this->params()->fromRoute('videoSlug')
                        ));

                    } else { //if the slug in url is not correct, but the id is

                        $this->redirect()->toRoute('solo/videos', array('albumSlug' => $goodSlug))->setStatusCode(201);

                    } //if the id is correct
                } // if the id is incorrect
                else return $this->redirect()->toRoute('solo/videos');
            }
        } else {

            $request = $this->getRequest();

            $performer = $sm->get('em')->getRepository(\Application\Entity\User::class)
                ->getUserBySettings('domain', $request->getServer()->get('HTTP_HOST'));

            $videos = $this->getServiceLocator()
                ->get('doctrine.entitymanager.orm_default')->getRepository('Videos\Entity\Video')
                ->findBy(array('user' => $performer, 'status' => 1));

            //@TODO fix this as it is only made to show frontend
//            $perSecond = $this->getServiceLocator()
//                ->get('doctrine.entitymanager.orm_default')->getRepository('Application\Entity\Config')
//                ->findOneBy(array('var' => 'tokens_per_second'))->getVal();

            $perSecond = 2;

            return new ViewModel(array(
                'videos' => $videos,
                'perSecond' => $perSecond
            ));

        }
        return false;
    }

    /**
     * @return ViewModel
     *
     * Gets the static html from database and shows it depending on the param of the route
     */
    public function staticPagesAction()
    {
        $pageName = $this->params()->fromRoute('page');
        $page = $this->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default')->getRepository('Application\Entity\StaticPages')
            ->findOneBy(array('page' => $pageName), array(), 1);

        $view = new ViewModel(array(

            'page' => $page

        ));
        if ($pageName == 'faq') {

            $view->setTemplate('faq');

        }
        return $view;
    }

    /**
     * @deprecated -> moved to application process controller
     * @return JsonModel
     *
     * Schedule ajax returns the calendar events
     */
    public function scheduleAjaxAction()
    {

        $performerId = $this->getServiceLocator()->get('Solo/Session')->model->getId();

        $schedules = $this->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default')
            ->getRepository('Application\Entity\ModelSchedule')
            ->getModelScheduleById($performerId);

        $array = array();

        foreach ($schedules as $schedule) {
            $array[] = array(
                'date' => date('Y-m-d h:i:s', $schedule['date']),
                'type' => $schedule['type'],
                'title' => $schedule['title'],
                'description' => $schedule['description'],
                'url' => $schedule['url']
            );
        }

        return new JsonModel($array);

    }

    /**
     * @return mixed|JsonModel
     *
     * This function checks and returns the password
     */
    public function checkPassword()
    {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $params = $this->params()->fromPost();
            return $params;

        } else {

            return new JsonModel(array('message' => 'failed'));

        }

    }

    /**
     * @return bool|\Zend\Http\Response
     */
    public function purchaseAction()
    {
        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $post = $request->getPost()->toArray();
            $role = $this->zfcUserAuthentication()->getIdentity()->getRoles()[0]->getRoleId();

            $contentType = $post['contentType'];
            $contentId = $post['contentId'];
            $sm = $this->getServiceLocator();
            $service = $this->getServiceLocator()->get('Zf2SlugGenerator\SlugService'); //get slug service
            if ($contentType == 'video') {
                $video = $sm->get('doctrine.entitymanager.orm_default')->getRepository('Videos\Entity\Video');
                $price = $video->getPrice($contentId);
                $vid = $video->findOneBy(array('id' => $contentId));

                $goodSlugNoId = $service->create($vid->getTitle(), false); //slugify title
                $goodSlug = $goodSlugNoId . '-' . $vid->getId(); //add id to slug

            } elseif ($contentType == 'gallery') {
                $albumRepo = $sm->get('doctrine.entitymanager.orm_default')->getRepository('Images\Entity\Albums');
                $album = $albumRepo->findOneBy(array('id' => $contentId));

                $goodSlugNoId = $service->create($album->getName(), false); //slugify title
                $goodSlug = $goodSlugNoId . '-' . $album->getId(); //add id to slug

                $price = $album->getAmount();
            } elseif ($contentType == 'image') {
                $price = 0;
            } elseif ($contentType == 'blog_post') {
                $blogRole = $this->zfcUserAuthentication()->getIdentity()->getRoles()[0]->getRoleId();
                if ($blogRole == 'member') $blogRole = 'members;';
                $blogRepo = $sm->get('doctrine.entitymanager.orm_default')
                    ->getRepository('Application\Entity\BlogPosts');
                $blog = $blogRepo->findOneBy(array('id' => $contentId));

                $blogAccessRepo = $sm->get('doctrine.entitymanager.orm_default')
                    ->getRepository('Application\Entity\BlogAccess');

                $goodSlugNoId = $service->create($blog->getTitle(), false); //slugify title
                $goodSlug = $goodSlugNoId . '-' . $blog->getId(); //add id to slug

                $price = $blogAccessRepo->findOneBy(array('idPost' => $contentId))->getChips();

            } else {

                return false;

            }

            $entityManager = $sm->get('doctrine.entitymanager.orm_default');
            $userId = $this->zfcUserAuthentication()->getIdentity()->getId();

            $user = $sm->get('doctrine.entitymanager.orm_default')->getRepository('Application\Entity\User')
                ->findOneBy(array('id' => $userId));
            $chips = $user->getChips();

            if ($chips >= $price) {

                $user->setChips($chips - $price); //remove the money from user account
                $entityManager->persist($user);
                $entityManager->flush(); //write to database

                //add data to purchased content database
                $contentEntity = new PurchasedContent();
                $contentEntity->setUser($user);
                $contentEntity->setUserType($role);
                $contentEntity->setContentId($contentId);
                $contentEntity->setContentType($contentType);
                $contentEntity->setAmount($price);
                $entityManager->persist($contentEntity);
                $entityManager->flush();

                //flush it
                if ($contentType == 'video') {
                    //redirect to video
                    return $this->redirect()->toRoute('solo/videos', array('videoSlug' => $goodSlug));
                } else if ($contentType == 'gallery') {

                    //redirect to album
                    return $this->redirect()->toRoute('solo/albums', array('albumSlug' => $goodSlug));

                } else if ($contentType == 'blog_post') {

                    return $this->redirect()->toRoute('solo/blog', array('slug' => $goodSlug));

                }

            } else {

                return false;

            }

        } else {

            echo "the request is not post";
            return false;

        }
        return false;
    }

    /**
     * @return ViewModel
     */
    public function profileAction()
    {

        $sm = $this->getServiceLocator();
        $username = $this->params()->fromRoute('username');
        $userRepo = $sm->get('doctrine.entitymanager.orm_default')->getRepository('Application\Entity\User');
        $user = $userRepo->findOneBy(array('username' => $username));
        if ($user) {
            if ($user->getBirthday() != null) {
                $birthDate = $user->getBirthday()->format('m/d/Y');
                $birthDate = explode("/", $birthDate);
                //get age from date or birthdate
                $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
                    ? ((date("Y") - $birthDate[2]) - 1)
                    : (date("Y") - $birthDate[2]));
            } else {

                $age = null;
            }
            $roles = $user->getRoles();
            if (!empty($roles)) {

                if ($user->getRoles()[0]->getRoleId() == 'performer') {


                    $profileSettings = $this->getServiceLocator()->get('em')->getRepository('Application\Entity\User')
                        ->getProfileSettings($user->getId());
                    $profile = $this->getServiceLocator()->get('em')->getRepository('Application\Entity\User')
                        ->getPerformerProfile($user->getId());

                    return new ViewModel(array(
                        'userProfile' => $user,
                        'age' => $age,
                        'model' => 1,
                        'profile' => $profile,
                        'modelProfile' => $profileSettings
                    ));

                } else {

                    return new ViewModel(array('userProfile' => $user, 'age' => $age));
                }
            } else {

                return new ViewModel(array('userProfile' => $user, 'age' => $age));


            }


        } else {

            return new ViewModel(array('notFound' => 1));

        }
    }
    public function pledgeAction()
    {

        $friends = array();
        $criteriaApproved = Criteria::create()->where(Criteria::expr()->eq("status", 1));
        $sl = $this->getServiceLocator();
        $modelId = $this->user()->getId();

        $friendsAll = $sl->get('doctrine.entity_manager.orm_default')
            ->getRepository('Application\Entity\User')
            ->find($this->user()->getId(),null,array("limit" => 1))
            ->getFriends()
            ->matching($criteriaApproved);


        foreach ($friendsAll as $friend) {

            $friends[] = $friend;

        }

        return new ViewModel(array('pledges' => $friends));

    }

    public function friendsAction()
    {
        $friends = array();
        $criteriaApproved = Criteria::create()->where(Criteria::expr()->eq("status", 1));
        $sl = $this->getServiceLocator();
        $modelId = $sl->get('Solo/Session')->model->getId();

        $friendsAll = $sl->get('doctrine.entity_manager.orm_default')
            ->getRepository('Application\Entity\User')
            ->find($this->user()->getId(),null,array("limit" => 1))
            ->getFriends()
            ->matching($criteriaApproved);

        foreach ($friendsAll as $friend) {

            $friends[] = $friend;

        }

        return new ViewModel(array('friends' => $friends));

    }

}
