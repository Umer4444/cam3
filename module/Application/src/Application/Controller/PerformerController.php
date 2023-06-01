<?php

namespace Application\Controller;

use PerfectWeb\Core\Traits;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

/**
 * Class PerformerController
 *
 * @package Application\Controller
 */
class PerformerController extends AbstractActionController
{

    use Traits\EntityManager;

    public function profileAction()
    {}

    public function blogAction()
    {}

    public function friendsAction()
    {}

    public function offersAction()
    {
//        die('offers');
    }

    public function watchAction()
    {
    }

    public function listAction()
    {
        return [
            'performers' => $this->getEntityManager()
                ->getRepository(\Application\Entity\User::class)
                ->getPerformers()
        ];
    }

    public function timelineAction()
    {

        $filter = $this->params()->fromRoute('filter');

        return $this->getServiceLocator()
                    ->get('Application\Widgets\TimeLine\TimeLineAggregator')
                    ->aggregateData($this->user()->getUser(), $filter);
    }

    public function videosAction()
    {}

    public function wallAction()
    {}

    public function picturesAction()
    {}

    public function callAction()
    {

        $this->layout('layout/bare');
        $data = [];
        if (!$this->user()->getUser()->getPhone()) {
            $data["message"] = "Model has no phone number";
        }
        else {

            if ($this->getRequest()->isPost() && $this->params()->fromPost("callButton")) {

                if (!$this->params()->fromPost("phone")) {
                    $data["message"] = "Enter your phone number";
                    return $data;
                }

                // Set our Account SID and AuthToken
                $sid = 'ACea2ff76988a2536ede2661687d571889';
                $token = '48d37212e0c477274cd5b3ed403e788b';

                $capability = new \Services_Twilio_Capability($sid, $token);
                $capability->allowClientOutgoing('AP9bbbb41fc8287074ccb280a7fad1e986');

                // Instantiate a new Twilio Rest Client
                $client = new \Services_Twilio($sid, $token);

                try {

                    $domain = $this->getServiceLocator()->get('ViewRenderer')->serverUrl();

                    // Initiate a new outbound call
                    $call = $client->account->calls->create(
                        $this->user()->getUser()->getPhone(),
                        $this->params()->fromPost("phone"),
                        $domain.'/call/callback/performer/'.$this->user()->getUser()->getId().
                            ($this->identity() ? '/user/'.$this->identity() :''),
                        ["StatusCallback" => $domain.'/call/callback/']
                    );

                    $data["message"] = $call->sid;
                    $data["inCall"] = true;

                }
                catch (\Exception $e) {
                    $data["message"] = $e->getStatus() . ' ' . $e->getMessage();
                }

            }
        }

        return $data;

    }

    // @todo checkthis
    /**
     * @return JsonModel
     * Json model. ajax..
     */
    public function changeStatusAction()
    {
        if ($this->getRequest()->isPost()) {
            $post = $this->params()->fromPost();

            if (isset($post['status']) && isset($post['id'])) {

                $commentsRepo = $this->getServiceLocator()->get('em')
                    ->getRepository('Application\Entity\RbComments')
                    ->findOneBy(array('id' => (int)$post['id']));

                $commentsRepo->setVisible((int)$post['status']);

                $this->getServiceLocator()->get('em')->persist($commentsRepo);
                $this->getServiceLocator()->get('em')->flush();

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

    // @todo checkthis
    public function tableAjaxAction()
    {
        $table = new \Application\Table\ModelsTable();
        $form = $table->getForm();

        //I don't know if it necesary to validate that request is POST
        $request = $this->getRequest();

        $filter = $table->getFilter();
        $form->setInputFilter($filter);
        $form->setData($request->getPost());

        //Optional
        $array = array('tip','private','spy');
        if ($form->isValid()) {

            $adapter = $this->getServiceLocator()->get('zfdb_adapter');
            $models = new \Application\Model\Models($adapter);
            $source = $models->fetchAllModels();

            $table->setAdapter($this->getDbAdapter())
                ->setSource($source)
                ->setParamAdapter($this->getRequest()->getPost());

            return $this->htmlResponse($table->render());
        }

        throw new \Exception('incorrect data sent in performer controller');

    }

    // @todo checkthis
    public function htmlResponse($html)
    {
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent($html);
        return $response;
    }

}