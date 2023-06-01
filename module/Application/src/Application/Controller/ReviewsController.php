<?php
namespace Application\Controller;

use Application\Entity\Resource;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class ReviewsController
 * @package Application\Controller
 */
class ReviewsController extends AbstractActionController
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
        $reviews = $this->getServiceLocator()
	                         ->get('Doctrine\ORM\EntityManager')
	                         ->getRepository('Application\Entity\Reviews')
	                         ->findAll();

        return new ViewModel(array(

                'reviews' => $reviews
            )
        );

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


                $reviewsRepo = $this->getEntityManager()
                    ->getRepository('Application\Entity\Reviews')
                    ->findOneBy(array('id' => (int)$post['id']));

                $reviewsRepo->setActive((int)$post['status']);

                $this->getEntityManager()->persist($reviewsRepo);
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
        $table = new \Application\Table\ReviewsTable();
        $form = $table->getForm();

        //I don't know if it necesary to validate that request is POST
        $request = $this->getRequest();

        $filter = $table->getFilter();
        $form->setInputFilter($filter);
        $form->setData($request->getPost());

        //Optional
        if ($form->isValid()) {
            $em = $this->getEntityManager();
            $queryBuilder = $em->createQueryBuilder();
            $queryBuilder->add('select', 'r, u')
                ->from('\Application\Entity\Reviews','r')
                ->leftJoin
                ('r.user','u');

            $table->setAdapter($this->getDbAdapter())
                ->setSource($queryBuilder)
                ->setParamAdapter($this->getRequest()->getPost());

            return $this->htmlResponse($table->render('custom', 'table-custom'));
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