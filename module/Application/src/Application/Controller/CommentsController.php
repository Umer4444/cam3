<?php
namespace Application\Controller;

use Application\Entity\Resource;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class CommentsController
 * @package Application\Controller
 */
class CommentsController extends AbstractActionController
{
    private $entityManager = null;

    /**
     *
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $dbAdapter;
    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();

    }

    /**
     * @return JsonModel
     * Json model. ajax..
     */
    public function changeStatusAction()
    {


        if ($this->getRequest()->isPost()) {
            $post = $this->params()->fromPost();

            if (isset($post['status']) && isset($post['id'])) {

                $id = (int)$post['id'];
                $status = (int)$post['status'];

                $commentsRepo = $this->getEntityManager()
                    ->getRepository('Application\Entity\RbComments')
                    ->findOneBy(array('id' => $id));

                $commentsRepo->setVisible($status);

                $this->getEntityManager()->persist($commentsRepo);
                $this->getEntityManager()->flush();

                $response = array(
                    'status' => 'success',

                );
            } else {
                $response = array(
                    'status' => 'fail',
                    'message' => "Parameters sent incorrectly"
                );
            }
        } else {
            $response = array(
                'status' => 'fail',
                'message' => "No post request"
            );
        }

        return new JsonModel($response);
    }

    /**
     * @return JsonModel
     * Json model. ajax..
     */
    public function updateCommentAction()
    {


        if ($this->getRequest()->isPost()) {
            $post = $this->params()->fromPost();

            if ((isset($post['name'])   && isset($post['pk']) && isset($post['value']))
            ) {
                $id = (int)$post['pk'];
                $value = $post['value'];

                $commentsRepo = $this->getEntityManager()
                    ->getRepository('Application\Entity\RbComments')
                    ->findOneBy(array('id' => $id));

                if($post['name'] == 'status') {
                    $commentsRepo->setVisible((int)$value);
                } elseif ($post['name'] == 'content') {
                    $commentsRepo->setContent($value);
                }
                $this->getEntityManager()->persist($commentsRepo);
                $this->getEntityManager()->flush();

                $response = array(
                    'status' => 'success',

                );
            } else {
                $response = array(
                    'status' => 'fail',
                    'message' => "Parameters sent incorrectly"
                );
            }
        } else {
            $response = array(
                'status' => 'fail',
                'message' => "No post request"
            );
        }

        return new JsonModel($response);
    }

    /**
     * @return array|null|object
     * private function get Entity Manager.
     */
    private function getEntityManager()
    {

        if (!$this->entityManager)
            $this->entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        return $this->entityManager;
    }

    public function tableAjaxAction()
    {
        $table = new \Application\Table\CommentsTable();
        $form = $table->getForm();

        //I don't know if it necesary to validate that request is POST
        $request = $this->getRequest();

        $filter = $table->getFilter();
        $form->setInputFilter($filter);
        $form->setData($request->getPost());

        //Optional
        if ($form->isValid()) {
            $em = $this->getEntityManager();

            $repo = $em->getRepository('Application\Entity\RbComments');
            $comments = $repo->getAllComments();


            $table->setAdapter($this->getDbAdapter())
                ->setSource($comments)
                ->setParamAdapter($this->getRequest()->getPost());

            return $this->htmlResponse($table->render('custom', 'table-comments'));
        } else {
            //Indication the wrong data currently not supported
        }


    }

    /**
     *
     * @return \Zend\Db\Adapter\Adapter
     */
    public function getDbAdapter()
    {
        if (!$this->dbAdapter) {
            $sm = $this->getServiceLocator();
            $this->dbAdapter = $sm->get('zfdb_adapter');
        }
        return $this->dbAdapter;
    }

    public function htmlResponse($html)
    {
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent($html);
        return $response;
    }

}