<?php

namespace Application\Controller;

use RbComment\Controller\CommentController;
use RbComment\Model\Comment;
use RbComment\Form\CommentForm;
use PerfectWeb\Core\Traits;

/**
 * Class CustomCommentsController
 * @package Application\Controller
 */
class CustomCommentsController extends CommentController {

    use Traits\EntityManager;

    /**
     * @return \Zend\Http\Response
     */
    public function addAction()
    {
        $em = $this->getEntityManager();
        $config = $this->getServiceLocator()->get('Config');
        $rbCommentConfig = (object)$config['rb_comment'];

        $form = new CommentForm($rbCommentConfig->strings);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $comment = new Comment();
            $form->setInputFilter($comment->getInputFilter());
            $form->setData($request->getPost());
            $url = $this->getRequest()->getHeader('Referer')->getUri();

            if ($form->isValid()) {

                $comment->exchangeArray($form->getData());
                $parentId = $form->getData()['id'];
                // Set default visibility from config
                $comment->visible = $rbCommentConfig->default_visibility;

                // If akismet is enabled check for spam
                if (($rbCommentConfig->akismet['enabled'] === true) &&
                    $this->isSpam($comment, $rbCommentConfig)
                ) {
                    $comment->spam = 1;
                    $comment->visible = 0;
                }
                $rbCommentRepo = $em->getRepository('Application\Entity\RbComments');

                $parent = $rbCommentRepo->find($parentId);

                //save with entity
                $rbComment = new \Application\Entity\RbComments();
                $rbComment->setThread($comment->thread);
                $rbComment->setUri($comment->uri);
                $rbComment->setAuthor($comment->author);
                $rbComment->setContact($comment->contact);
                $rbComment->setContent($comment->content);
                $rbComment->setVisible($comment->visible);
                $rbComment->setSpam($comment->spam);
                $rbComment->setParent($parent);

                $em->persist($rbComment);
                $em->flush();
                $comment->id = $rbComment->getId();

                // Send email if active and not spam
                if (($rbCommentConfig->email['notify'] === true) &&
                    ($comment->spam === 0)
                ) {
                    $this->rbMailer($comment);
                }

                return $this->redirect()->toUrl($url);
            } else {
                $this->flashMessenger()->setNamespace('RbComment');
                $this->flashMessenger()->addMessage(json_encode($form->getMessages()));

                return $this->redirect()->toUrl($url);
            }
        }
    }
}