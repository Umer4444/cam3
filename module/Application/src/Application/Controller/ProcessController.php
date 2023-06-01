<?php

namespace Application\Controller;

use Doctrine\ORM\Query;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use PerfectWeb\Core\Traits;

/**
* Class ProcessController
* @package Application\Controller
*
*/
class ProcessController extends AbstractActionController
{
    use Traits\EntityManager;

    /**
    * Method for processing request for likes and rating, reviews and friends
    * @return array|JsonModel
    */
    public function indexAction()
    {

        $action = $this->params()->fromPost('action', false);
        $usersRepo = $this->getServiceLocator()
                          ->get('doctrine.entitymanager.orm_default')
                          ->getRepository('Application\Entity\User');

        if(!$action) {
            $action = $this->params()->fromRoute('getdata');
        }

        $response = array();
        if ($action == 'review') {

            $reviewService = $this->getServiceLocator()->get('Application\Service\ReviewFormService');
            $user = $this->zfcUserAuthentication()->getIdentity();

            $response = $reviewService->reviewForm($this->params()->fromPost(), $user);

            return $response;

        }
        elseif ($action == "disclaimer") {

            $pagesRepo = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')->getRepository('Application\Entity\StaticPages');
            $disclaimer = $pagesRepo->findOneByPage("disclaimer_landing_page");

            if($disclaimer){
                $response = array("content" => $disclaimer->getContent());
            }

        } elseif ($action == "saveStyle") {
            $response  = $this->saveFontStyle($this->params()->fromPost());

        } elseif($action == "autocomplete-username") {
            //chat @ autocomplete
            $response  = $this->chatUsernameAutocomplete($this->params()->fromPost());
        }
        elseif($action == "country") {

            $response  = $this->getServiceLocator()
                          ->get('doctrine.entitymanager.orm_default')
                          ->getRepository(\Application\Entity\Country::class)->findAll(Query::HYDRATE_ARRAY);

        }
        elseif($action == "send-tip") {
            //tipped
            $response  = $this->sendTip($this->params()->fromPost());
        }

        return new JsonModel($response);
    }

    /**
     * hide model for user or model
     * @return JsonModel
     */
    public function hideModelAction() {

        $params = $this->params()->fromPost();

        $for = $params['for'];
        $modelId = $params['model'];


        if(is_null($modelId)) return new JsonModel(array('message' => 'failed'));

        if(empty($for)) {

            if($this->zfcUserAuthentication()->hasIdentity()) {
                $for = $this->zfcUserAuthentication()->getIdentity()->getId();
            } else {

                return new JsonModel(array('message' => 'failed'));

            }
        }

        $entityManager = $this->getServiceLocator()->get('doctrine.entity_manager.orm_default');
        $userRepo = $entityManager->getRepository('Application\Entity\User');


        $hideFor = $userRepo->findOneBy(array('id' => $for));
        $model = $userRepo->findOneBy(array('id' => $modelId));

        $hideFor->addHiddenModel($model);



        $entityManager->persist($hideFor);
        $entityManager->flush();

        return new JsonModel(array('message' => 'success'));

    }

    public function saveFontStyle($params = null) {
        $response = array();
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        if($user = $this->zfcUserAuthentication()->hasIdentity() && strpos($params['user_name'], 'guest') === false) {

            $user = $this->zfcUserAuthentication()->getIdentity();

            $userRepo = $em->getRepository('Application\Entity\User');
            $settings = $userRepo->getProfileSettings($user->getId());

            $resourceRepo = $em->getRepository(\PerfectWeb\Core\Entity\Resource::class);

            if (!isset($settings['chat_font']) || empty($settings['chat_font'])) {


                $resource = $resourceRepo->findOneBy(array('group' => 'profile', 'key' => 'chat_font'));

                $accessEntity = new \PerfectWeb\Core\Entity\UserAccess();
                $accessEntity->setUser($user);
                $accessEntity->setResource($resource);
                $accessEntity->setPermission('w');
                $em->persist($accessEntity);

                $settingsEntity = new \Application\Entity\ResourceValue();
                $settingsEntity->setUser($user);
                $settingsEntity->setUserId((int)$user->getId());
                $settingsEntity->setValue(json_encode($params));
                $settingsEntity->setKeyReference($resource);
                $em->persist($settingsEntity);
            } else {

                foreach ($user->getSettings() as $profileSettingsEntity) {
                    if ($profileSettingsEntity->getKeyReference()->getKey() == "chat_font") {
                        $profileSettingsEntity->setValue(json_encode($params));
                    }
                }
                $em->persist($user);
            }

            $em->flush();
        }
       //update webchat users
        $sql = "UPDATE webchat_users SET chat_font='" .json_encode($params) . "' WHERE id_user = '" . $params['user_id'] . "' LIMIT 1";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();


        //update session value for zf1 usage @TODO to be deprecarted
        $_SESSION['user']['chat_font'] = json_encode($params);

        $response["status"] = "success";

        return $response;
    }

    public function rateSessionAction()
    {

        $viewModel = new JsonModel();

        $params = $this->params()->fromPost();
        $response = array();

        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        if(isset($params['model'])) $params['idBox'] = $params['model'];


        if(isset($params['idBox'])) {

            if(!($chat = $em->getRepository('Application\Entity\WebchatSessions')
                            ->findOneBy(['model' => $params['idBox']])))
            {
                return;
            }

            if(!($history = $em->getRepository('Application\Entity\WebchatHistory')->find($chat->getSessionIdentifier()))) {
                $history = new \Application\Entity\WebchatHistory();
                $history->setId($chat->getSession());
                $history->setStart($chat->getTimer());
                $history->setUser((int)$params['idBox']);
                $em->persist($history);
                $em->flush();
            }


        }
        else {
            return array('status' => 'fail', 'message' => "no model");
        }

        if(isset($params['extra']) && isset($params['rate'])){
            $for = array_pop(explode('_', $params['extra']));

            if (isset($_SESSION["rating"][$chat->getSession()][$for])) {
                $response["status"] = "fail";
                $response["message"] = "Allready voted";
                echo json_encode($response);
                exit;
            }


                switch($for){
                    case 'light':
                        $history->setVotesLight($history->getVotesLight() + 1);
                        $history->setRatingLight(($history->getRatingLight() + (int)$params['rate'])/ $history->getVotesLight());
                        break;
                    case 'sound':
                        $history->setVotesSound($history->getVotesSound() + 1);
                        $history->setRatingSound(($history->getRatingSound() + (int)$params['rate']) / $history->getVotesSound());
                        break;
                    case 'appearance':
                        $history->setVotesAppearance($history->getVotesAppearance() + 1);
                        $history->setRatingSound(($history->getRatingAppearance() + (int)$params['rate']) / $history->getVotesAppearance());
                        break;

                    case 'surround':
                        $history->setVotesSurround($history->getVotesSurround() + 1);
                        $history->setRatingSurround(($history->getRatingSurround() + (int)$params['rate']) / $history->getVotesSurround());
                        break;

                }

                if(!isset($_SESSION["rating"][$chat->getSession()][$for])) {
                    $em->persist($history);
                    $em->flush($history);
                    $_SESSION["rating"][$chat->getSession()][$for] = true;

                    $response["status"] = "success";
                    $response["message"] = "Vote saved";
                }


                echo json_encode($response);
                exit;
            }



        $viewModel->setVariables(
            array(
                'params' => $params,
                'history' => $history,

            )
        );

        return $viewModel;
    }

    private function chatUsernameAutocomplete($params = array() ){
        $users = array();
        if(isset($params['query'])
            && !empty($params['query'])
            && isset($params['model_id'])
            && !empty($params['model_id'])
        ){
        //    search names in chat user list
            $em = $this->getEntityManager();
                $wsRepo = $em->getRepository('Application\Entity\WebchatUsers');

               $users = $wsRepo->searchName($params['model_id'], $params['query']);
            }

        return $users;

    }

    public function autoCompleteAction()
    {

        $request = $this->getRequest();

        if ($request->isPost()) {

            $searchString = $request->getPost('q');

            $userRepo = $this->getEntityManager()->getRepository('Application\Entity\User');
            $results = $userRepo->getAutoCompleteResults($searchString);

            return new JsonModel(array(json_encode($results)));
        }

        return new JsonModel((array('messages' => 'failed')));
    }

    /**
     * @return JsonModel
     *
     * Schedule ajax returns the calendar events
     */
    public function eventCalendarAction()
    {
        $performerId = (int)$this->params()->fromQuery('performer', false);

        if(
            !$performerId &&
            (
                $website = $this->getEntityManager()
                            ->getRepository(\Application\Entity\Website::class)
                            ->findOneBy(array('name' => $this->getRequest()->getServer()->get('HTTP_HOST')))
            )
            instanceof \Application\Entity\Website
        )
        {
            $performerId = $website->getUser()->getId();
        }

        $schedules = $this->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default')
            ->getRepository('Application\Entity\ModelSchedule')
            ->getModelScheduleById(($performerId) ? $performerId: null);

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

    public function sendTip($post) {

        $response['status'] = 'fail';
        $response['message'] = 'No data';

        if(!isset($post['performer'])){
            $response['message'] = 'No performer selected';
        } else if (!isset($post['tip_value'])) {
            $response['message'] = 'No tip sent';
        } else if ($this->zfcUserAuthentication()->hasIdentity()) {
            $tipValue = (int)$post['tip_value'];
            $userFrom = $this->zfcUserAuthentication()->getIdentity();
            //@todo get performer , move chips from one wallet to another
            $entityManager = $this->getServiceLocator()->get('doctrine.entity_manager.orm_default');
            $userRepo = $entityManager->getRepository('Application\Entity\User');
            $userTo = $userRepo->findOneById((int)$post['performer']);

            if($userTo) {
                if($userFrom->getWallet()->getAmount() >= $tipValue) {
                    $userTo->getWallet()->sendAmount($tipValue);
                    $userFrom->getWallet()->sendAmount('-' . $tipValue);


                    $response['message'] = 'Saved';
                    $response['status'] = 'success';

                } else {
                    $response['message'] = 'Not enough chips';
                    $response['status'] = 'fail';
                }
            }

        } else {
            $response['message'] = 'Not logged in';
        }

        return $response;
    }


}
