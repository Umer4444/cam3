<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use PerfectWeb\Core\Traits;

/**
 * Class PhotosController
 * @package Application\Controller
 */
class VideosController extends AbstractActionController
{
    use Traits\EntityManager;

    //items statuses
    const ITEM_APPROVED = 1;
    const ITEM_PENDING = 0;
    const ITEM_DENIED = -1;

    /**
     * @return array
     *
     * indexAction for indexController from store
     */
    public function indexAction()
    {
        $em = $this->getEntityManager();
        $params = $this->params()->fromRoute();

        $status = null;
        $page = 1;
        $itemsPerPage = 30;

        if (isset($params["page"])) {
            $page = $params["page"];
        }
        if (isset($params["selected_filter"])) {
            if ($params["selected_filter"] == "pending") $status = self::ITEM_PENDING;
            if ($params["selected_filter"] == "approved") $status = self::ITEM_APPROVED;
            if ($params["selected_filter"] == "denied") $status = self::ITEM_DENIED;
        } else {
            $params["selected_filter"] = 'all';
        }
        if (isset($params['selected_filter_type'])) {
            $type = $params['selected_filter_type'];
        } else {
            $type = 'all';
        }
        $repository = $em->getRepository('Videos\Entity\Video');
        $role = $this->zfcUserAuthentication()->getIdentity()->getRoles()[0]->getRoleId();
        if ($role == 'admin' || $role == 'super_admin') {
            $id = null;
            $status = false;
        } else {
            $id = $this->zfcUserAuthentication()->getIdentity()->getId();
        }
        $defaults = $em->getRepository('Application\Entity\User')->getProfileSettings($id, 'Video');

        $count = $repository->countItems($status, $type, $id);

        $offset = $itemsPerPage * ($page - 1);

        $items = $repository->getItems($status, $type, $id, $offset, $itemsPerPage);

        $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\Null($count));
        $paginator->setItemCountPerPage($itemsPerPage);

        if ($page) $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'defaults' => $defaults,
            'items' => $items,
            'page' => $page,
            'selectedFilter' => $params["selected_filter"],
            'selectedFilterType' => $params['selected_filter_type'],
            'paginator' => $paginator
        ));
    }

    /**
     * @return JsonModel
     */
    public function changeStatusAction()
    {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest() && $this->isAllowed("manage-videos", "edit")) { // If it's ajax call

            $em = $this->getEntityManager();
            $itemsRepo = $em->getRepository('Videos\Entity\Video');

            $items = $request->getPost()->toArray();

            $approvedDenied = array();

            $pendingItems = array();
            $approvedItems = array();
            $deniedItems = array();

            if (isset($items["pendingItems"]) && !empty($items["pendingItems"])) {
                $pendingItems = explode(',', $items["pendingItems"]);
            }
            if (isset($items["approvedItems"]) && !empty($items["approvedItems"])) {
                $approvedItems = explode(',', $items["approvedItems"]);
            }
            if (isset($items["deniedItems"]) && !empty($items["deniedItems"])) {
                $deniedItems = explode(',', $items["deniedItems"]);
            }

            $allItems = array_unique(array_merge($approvedItems, $pendingItems, $deniedItems));

            if (count($allItems) > 0) {
                $foundItems = $itemsRepo->findBy(array('id' => $allItems));

                foreach ($foundItems as $foundItem) {
                    if (in_array($foundItem->getId(), $approvedItems)) $foundItem->setActive(self::ITEM_APPROVED);
                    if (in_array($foundItem->getId(), $pendingItems)) $foundItem->setActive(self::ITEM_PENDING);
                    if (in_array($foundItem->getId(), $deniedItems)) $foundItem->setActive(self::ITEM_DENIED);

                    $em->persist($foundItem);
                }

                $em->flush();
            }

            return new JsonModel (array(
                'approved' => $approvedDenied,
                'message' => 'success'
            ));

        } else {
            return new JsonModel (array(
                'approved' => 'no',
                'message' => 'fail - not allowed'
            ));
        }
    }

    /**
     * @return JsonModel
     */
    public function setVideoAction()
    {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest() && $this->isAllowed("manage-videos", "view")) { // If it's ajax call

            if (!$this->isAllowed('set-video', 'edit')) {
                return JsonModel(array(
                    "message" => "Not allowed",
                    "status" => "important"
                ));
            }

            $params = $request->getPost()->toArray();

            $actionsMap = array(
                "intro_video",
                "bio_video",
                "turns_on_video",
                "turns_off_video",
                "private_do_video",
                "private_dont_video",
                "interests_hobbies_video",
                "room_rules_video"
            );

            if (!in_array($params["action"], $actionsMap)) {
                return new JsonModel(array('message' => 'Undefined action', "status" => 'fail'));
            }

            $em = $this->getServiceLocator()->get('em');
            $videoRepo = $em->getRepository('Videos\Entity\Video');

            $user = $this->zfcUserAuthentication()->getIdentity();

            $item = $videoRepo->findOneBy(array(
                'id' => (int)$params['id'],
                'userId' => $user->getId(),
            ));

            $resourceRepo = $em->getRepository(\Application\Entity\Resource::class);
            $resource = $resourceRepo->findOneBy(array(
                'group' => 'profile',
                "key" => $params["action"]
            ));

            $settingRepo = $em->getRepository(\Application\Entity\ResourceValue::class);
            $settingsEntity = $settingRepo->findOneBy(array(
                'user' => $user,
                'keyReference' => $resource
            ));

            if (!$settingsEntity || !$settingsEntity->getId()) {
                $settingsEntity = new \Application\Entity\ResourceValue();
                $settingsEntity->setUser($user);
                $settingsEntity->setKeyReference($resource);
            }

            if($settingsEntity->getValue() == (int)$params['id']) {
                $settingsEntity->setValue('');
            }
            else {
                $settingsEntity->setValue($item->getId());
            }

            $em->persist($settingsEntity);
            $em->flush();

            return new JsonModel(array(
                'message' => "Saved",
                'status' => "success"
            ));
        }
    }

}
