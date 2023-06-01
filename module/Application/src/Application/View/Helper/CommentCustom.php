<?php

namespace Application\View\Helper;

use Nicovogelaar\Paginator\Exception;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Application\Repository\RbCommentsRepository;
use Application\Extended\RbComment\Form\CustomCommentForm;
/**
 * Class CommentCustom
 * @package Application\View\Helper
 */
class CommentCustom extends AbstractHelper implements ServiceLocatorAwareInterface
{
    private $serviceLocator;

    protected $themes = array(
        'default' => true,
        'bootstrap3' => true
    );

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function __invoke($theme = 'default', $guest = null, $anonymous = null, $id = null)
    {
        // If using a custom theme/partial do not append the prefix
        $invokablePartial = isset($this->themes[$theme])
            ? 'rbcomment/theme/' . $theme
            : $theme;

        $serviceManager = $this->getServiceLocator()->getServiceLocator();
        $viewHelperManager = $serviceManager->get('viewhelpermanager');

        $requestUri = $serviceManager->get('router')->getRequestUri();
        // fix for when the helper is used from zf1 and we dont have the router matching any route
        $uri = is_null($requestUri) ? $_SERVER['REQUEST_URI'] : $requestUri->getPath();
        $thread = sha1($uri);
        $validationMessages = $viewHelperManager->get('flashMessenger')
            ->getMessagesFromNamespace('RbComment');

        $config = $serviceManager->get('Config');
        $strings = $config['rb_comment']['strings'];

        if ($guest) {

            if (isset($id)) {
                $uri = '/guestbook/' . $id;
            } else {

                try {

                    $model = $this->view->user()->getUser();

                    if (is_object($model)) {
                        $uri = '/guestbook/' . $model->getId();
                    } else {
                        throw new \Exception('You cannot invoke this viewhelper for guestbook without using the id parameter
                  Available parameters are $theme = "default", $guest -> if guestbook, $anonymous->if it everyone or just members will post,
                  $id -> model id for guestbook');
                    }

                } catch (Exception $e) {
                    echo $e->getMessage();
                    return;
                }


            }

            $thread = sha1($uri);
            $strings['content'] = 'Message';
            $strings['comments'] = 'Messages';
            $strings['notsignedin'] = 'You are not signed in. To be able to leave a message, please ';
        }

//        if ($anonymous) { //leave it like this for the moment.
//die('test');
//            $zfcuser = false;
//        } else {

            $zfcuser = $config['rb_comment']['zfc_user']['enabled'];

       // }

        $auth = $serviceManager->get('zfcuser_auth_service');

        if ($auth->hasIdentity()) {
            $username = $auth->getIdentity()->getUsername();
        }

        if (!isset($username)) {
            $username = 'anonymous';
        }
        $pending = $serviceManager->get('doctrine.entitymanager.orm_default')->getRepository('Application\Entity\RbComments')
            ->getByUsernameForThread($thread, $username, RbCommentsRepository::pending);

        $pendingReview = null;
        if ($id && $auth->hasIdentity() &&
            ($auth->getIdentity()->getRoles()[0]->getRoleId() == "admin"
                || $auth->getIdentity()->getRoles()[0]->getRoleId() == "admin"
                || $auth->getIdentity()->getRoles()[0]->getRoleId() == "super_admin"
                || (
                    $auth->getIdentity()->getRoles()[0]->getRoleId() == "performer"
                    && (int)$id == $auth->getIdentity()->getId()
                )
            )
        ) {
            $pendingReview = $serviceManager->get('doctrine.entity_manager.orm_default')
                ->getRepository('Application\Entity\RbComments')
                ->getByUsernameForThread($thread, RbCommentsRepository::pending);
        }
        $comments = $serviceManager->get('doctrine.entity_manager.orm_default')
            ->getRepository('Application\Entity\RbComments')
            ->getByUsernameForThread($thread, null, RbCommentsRepository::all);
        $children = array();
        foreach ($comments as $comment) {
            if(!$comment->getParent()) {
                continue;
            }
            $children[$comment->getParent()->getId()][$comment->getId()] = $comment;
        }
        echo $viewHelperManager->get('partial')->__invoke($invokablePartial, array(
            'comments' => $comments,
            'children' => $children,
            'pending' => $pending,
            'pendingReview' => $pendingReview,
            'form' => new CustomCommentForm($strings),
            'thread' => $thread,
            'validationResults' => count($validationMessages) > 0
                ? json_decode(array_shift($validationMessages))
                : array(),
            'uri' => $uri,
            'strings' => $strings,
            'zfc_user' => $zfcuser,
            'gravatar' => $config['rb_comment']['gravatar']['enabled'],
        ));

    }
}
