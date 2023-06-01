<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Application\Form;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use PerfectWeb\Core\Traits;

/**
 * Class IndexController
 * @package Application\Controller
 */
class IndexController extends AbstractActionController
{

    use Traits\EntityManager;
    use Traits\User;

    public function indexAction()
    {
        $cookies = $this->getRequest()->getCookie();

        return new ViewModel(
            array(
                'disclaimer' => $cookies['disclaimer'],
                'chat' => $cookies['chat']
            )
        );
    }

    /**
     * Used for different noop actions
     */
    public function noopAction()
    {
        return new JsonModel();
    }

    public function performersAction()
    {
        $params = $this->params()->fromRoute();
        $limit = 30;
        $em = $this->getServiceLocator()->get("em");
        $userRepo = $em->getRepository("Application\\Entity\\User");
        $page = $params["page"];
        $offset = ($page-1) * $limit;

        $users = $userRepo->getPerformers($liveOnly = false, $active = true, $offset, $limit);
        foreach($users as $k => $user) {
            $user['profile'] = $userRepo->getProfileSettings($user["user"]->getId());
            $users[$k] = $user;
        }

        return new ViewModel(array(
            'users' => $users
        ));
    }

    /**
     * @return ViewModel
     */
    public function messagesAction()
    {
        $messageAction = $this->params()->fromRoute('type');
        $page = $this->params()->fromRoute('page');

        if (!empty($page)) {
            $page = (int)$page;
            $page = ($page < 1) ? 1 : $page; // If an invalid page is requested, then show the first page
        } // No page specified; show the first page
        else {
            $page = 1;
        }

        $itemsPerPage = 30;
        $messagesRepo = $this->getEntityManager()->getRepository('Application\Entity\Message');
        $id = $this->zfcUserAuthentication()->getIdentity()->getId();
        $unread = $messagesRepo->getUnreadMessages($id);
        $slug = $this->params()->fromRoute('slug');

        switch ($messageAction) {

            case 'inbox':

                if (isset($slug)) {

                    $id = end(explode('-', $slug));
                    $message = $messagesRepo->findBy(array('id' => $id));
                    if ($message[0]->getRead() == 0) {

                        $sm = $this->getServiceLocator();
                        $message[0]->setRead(1);
                        $this->getEntityManager()->persist($message[0]);
                        $this->getEntityManager()->flush();
                        $unread = $unread - 1;

                    }

                    return new ViewModel(array(
                        'type' => 'inbox',
                        'unread' => $unread,
                        'message' => $message[0]
                    ));

                } else {

                    $inbox = $messagesRepo->getUserMessages($id, $itemsPerPage, $itemsPerPage * ($page - 1), 'inbox');

                    $paginator = new \Zend\Paginator\Paginator(
                        new DoctrinePaginator(new ORMPaginator($inbox))
                    );
                    $paginator->setCurrentPageNumber($page);
                    $paginator->setItemCountPerPage($itemsPerPage);


                    return new ViewModel(array(
                        'type' => 'inbox',
                        'unread' => $unread,
                        'inbox' => $inbox,
                        'paginator' => $paginator,
                    ));

                }
                break;
            case 'outbox':
                if (isset($slug)) {

                    $id = end(explode('-', $slug));
                    $message = $messagesRepo->findBy(array('id' => $id));

                    return new ViewModel(array(
                        'type' => 'outbox',
                        'unread' => $unread,
                        'message' => $message[0]
                    ));

                } else {
                    $outbox = $messagesRepo->getUserMessages($id, $itemsPerPage, $itemsPerPage * ($page - 1), 'outbox');
                    $paginator = new \Zend\Paginator\Paginator(
                        new DoctrinePaginator(new ORMPaginator($outbox))
                    );
                    $paginator->setCurrentPageNumber($page);
                    $paginator->setItemCountPerPage($itemsPerPage);

                    return new ViewModel(array(
                        'type' => 'outbox',
                        'unread' => $unread,
                        'paginator' => $paginator,
                    ));
                }
                break;
            case 'archive':
                if (isset($slug)) {

                    $id = end(explode('-', $slug));
                    $message = $messagesRepo->findBy(array('id' => $id));

                    return new ViewModel(array(
                        'type' => 'archive',
                        'unread' => $unread,
                        'message' => $message[0]
                    ));

                } else {
                    $archive = $messagesRepo->getUserMessages($id, $itemsPerPage, $itemsPerPage * ($page - 1), 'archive');
                    $paginator = new \Zend\Paginator\Paginator(
                        new DoctrinePaginator(new ORMPaginator($archive))
                    );
                    $paginator->setCurrentPageNumber($page);
                    $paginator->setItemCountPerPage($itemsPerPage);

                    return new ViewModel(array(
                        'type' => 'archive',
                        'unread' => $unread,
                        'paginator' => $paginator,
                    ));

                }
                break;
            case 'compose':

                $friendsRepo = $this->getEntityManager()->getRepository('Application\Entity\Friends');
                $friendsDeepArray = $friendsRepo->getAllFriends($id);

                $friends = array();

                foreach ($friendsDeepArray as $friend) {

                    $friends[] = $friend['user']->getUsername();
                }
                $friends = json_encode($friends);

                $form = new Form\Message();
                $request = $this->getRequest();
                $post = $request->getPost();
                if ($post['reply'] == 'Reply') {

                    $username = $post['username'];

                    $type = $post['userType'];

                    $form->get('sendTo')->setValue($username);
                }
                if (!$post['reply']) {
                    if ($request->isPost()) { //cheking if form is post

                        $post = $request->getPost()->toArray();

                        $form->setValidationGroup('sendTo', 'subject', 'message', 'tip');

                        $form->setData($post);
                        if ($form->isValid()) {
                            $userRepo = $this->getEntityManager()->getRepository('Application\Entity\User');
                            $sendTo = $userRepo->findOneBy(array('username' => $post['sendTo']));

                            $sendToId = $sendTo->getId();
                            $sendToRole = $sendTo->getRoles()[0]->getRoleId();

                            if ($sendToRole == 'performer') {

                                $sendToRole = 'model';

                            }

                            $chips = $userRepo->findOneBy(array('id' => $id))->getChips();
                            if ($chips < $post['tip']) {

                                $form->get('tip')->setMessages(array('Not enough chips'));
                                return false;

                            }
                            if ($this->zfcUserAuthentication()->getIdentity()->getRoles()[0]->getRoleId() == 'performer') {

                                $role = 'model';

                            } else {

                                $role = $this->zfcUserAuthentication()->getIdentity()->getRoles()[0]->getRoleId();
                            }

                            $user = $userRepo->findOneBy(array('id' => $id));

                            if ($post['tip'] > 0) {

                                $user->setChips($user->getChips() - $post['tip']);
                                $this->getEntityManager()->persist($user);
                                $this->getEntityManager()->flush();

                            }

                            $messages = new \Application\Entity\Message();
                            $messages->setSenderId($user);
                            $messages->setSenderType($role);
                            $messages->setReceiverId($sendToId);
                            $messages->setReceiverType($sendToRole);
                            $messages->setSubject($post['subject']);
                            $messages->setMessage($post['message']);
                            $messages->setInbox(0);
                            $messages->setOutbox(0);
                            $messages->setSendDate(time());
                            $messages->setRead(0);
                            $messages->setTip($post['tip']);
                            $messages->setType('inbox');

                            $this->getEntityManager()->persist($messages);
                            $this->getEntityManager()->flush();

                            return $this->redirect()->toRoute('solo/messages', array('type' => 'outbox'));

                        }
                    }
                }

                return new ViewModel(
                    array(
                        'type' => 'compose',
                        'friends' => $friends,
                        'unread' => $unread,
                        'form' => $form
                    )
                );

                break;

        }

        $userRepo = $this->getEntityManager()->getRepository('Application\Entity\User');

        $userId = $this->zfcUserAuthentication()->getIdentity();
        $user = $userRepo->findOneBy(array('id' => $userId));
        $messagesRepo = $this->getEntityManager()->getRepository('Application\Entity\Message');
        $messages = $messagesRepo->findBy(array('receiverId' => $userId));
        $view = 0;
        if ($this->params()->fromRoute('id')) {

            $view = 1;
            $message = $messagesRepo->findOneBy(array('id' => $this->params()->fromRoute('id')));
            $sender = $userRepo->findOneBy(array('id' => $message->getSenderId()));
            if ($sender->getUsername()) {

                $sender = $sender->getUsername();
            } else {

                $sender = $sender->getEmail();
            }

            return new ViewModel(array('user' => $user, 'view' => $view, 'message' => $message, 'sender' => $sender));
        }

        return new ViewModel(array('user' => $user, 'messages' => $messages, 'view' => $view));

    }

    public function hallOfFameAction()
    {
        $performers = $this->getEntityManager()
            ->getRepository(\Application\Entity\User::class)
            ->getPerformers();

        return new ViewModel(['performers' => $performers]);
    }

    public function popularRoomsAction()
    {
        /**
         * @var \Application\Paginator\PopularPaginator
         */
        $paginator = $this->getServiceLocator()->get(\Application\Paginator\PopularPaginator::class);
        $paginator->setData(array_merge($this->params()->fromRoute(), ['route' => true]));

        return new ViewModel(['paginator' => $paginator]);

    }

    public function contestsAction()
    {
        /**
         * @TODO this is just wrong and needs to have other logic
         */
        $performers = $this->getEntityManager()
            ->getRepository('Application\Entity\User')
            ->findBy(array('status' => 1),array(),5,0);

        return new ViewModel(['performers' => $performers]);
    }

    public function wishlistAction()
    {}

    public function wishlistListAction()
    {
        /**
         * @TODO this is just wrong and needs to have other logic
         */

        $performers = $this->getEntityManager()
            ->getRepository('Application\Entity\User')
            ->findOneBy(['id' => $this->params()->fromRoute('id')]);

        return new ViewModel(['performer' => $performers]);

    }

    public function requestsAction()
    {
        /**
         * @TODO this is wrong and needs to have other logic
         */

        $users = $this->getEntityManager()
            ->getRepository('Application\Entity\User')
            ->findBy([],[],5,0);

        return new ViewModel(
            ['users' => $users]
        );

    }

    public function clubsAction()
    {
    }

    public function phoneListAction()
    {
        $performers = $this->getEntityManager()
            ->getRepository(\Application\Entity\User::class)
            ->getPerformers();

        return [
            'performers' => $performers
        ];
    }

    public function calendarAction()
    {

        date_default_timezone_set($this->params()->fromRoute('timezone'));
        $this->getEntityManager()
             ->createQuery("SET session time_zone = ".$this->params()->fromRoute('timezone')."");

    }

    public function filterAction ()
    {
    }

    public function presentationsAction()
    {
    }

    public function presentationAction()
    {
    }

    public function activityAction()
    {
        $filter = $this->params()->fromRoute('filter');
        $page = $this->params()->fromRoute('page');

         return $this->getServiceLocator()
                ->get('Application\Widgets\TimeLine\TimeLineAggregator')
                ->aggregateData(null, $filter, $page);

    }

    /**
     * @return ViewModel
     *
     */
    public function liveAction()
    {
        $categories = $this->getEntityManager()
            ->getRepository(\Application\Entity\Categories::class)
            ->findAll();

        $info = $this->getEntityManager()
            ->getRepository(\Application\Entity\Info::class)
            ->findAll();

        $performers = $this->getEntityManager()
            ->getRepository(\Application\Entity\User::class)
            ->getPerformers();

        return [
            'categories' => $categories,
            'info' => $info,
            'performers' => $performers
        ];

    }

}
