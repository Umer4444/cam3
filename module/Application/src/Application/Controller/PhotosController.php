<?php

namespace Application\Controller;

use PerfectWeb\Core\Utils\Status;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

/**
 * Class PhotosController
 * @package Application\Controller
 */
class PhotosController extends AbstractActionController
{

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

        $params = $this->params()->fromRoute();

        $status = null;
        $page = 1;
        $itemsPerPage = 30;

        if(isset($params["page"])) {
            $page = $params["page"];
        }
        if(isset($params["selected_filter"])) {
            if($params["selected_filter"] == "pending") $status = Status::INACTIVE;
            if($params["selected_filter"] == "approved") $status = Status::ACTIVE;
            if($params["selected_filter"] == "denied") $status = Status::REJECTED;
        } else {
            $params["selected_filter"] = 'all';
        }

        $repository = $this->getServiceLocator()
                            ->get('doctrine.entity_manager.orm_default')
                            ->getRepository('Images\Entity\Photo');

        $count = $repository->countItems($status);

        $offset = $itemsPerPage * ($page - 1);
        $items = $repository->getItems($status, 1, $offset, $itemsPerPage)->getScalarResult();
        $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\Null($count));
        $paginator->setItemCountPerPage($itemsPerPage);

        if ($page) $paginator->setCurrentPageNumber($page);


        return new ViewModel(array(
                    'items' => $items,
                    'page' => $page,
                    'selectedFilter' => $params["selected_filter"],
                    'paginator' => $paginator
        ));
    }

    /**
     * @return JsonModel
     */
    public function changeStatusAction() {

        $request = $this->getRequest();


        if ($request->isXmlHttpRequest()) { // If it's ajax call

            $em = $this->getServiceLocator()->get('doctrine.entity_manager.orm_default');
            $itemsRepo = $em->getRepository('Images\Entity\Photo');

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

            if(count($allItems) > 0) {
                $foundItems = $itemsRepo->findBy(array('id' => $allItems));

                foreach($foundItems as $foundItem) {
                    if(in_array($foundItem->getId(), $approvedItems)) $foundItem->setActive(self::ITEM_APPROVED);
                    if(in_array($foundItem->getId(), $pendingItems)) $foundItem->setActive(self::ITEM_PENDING);
                    if(in_array($foundItem->getId(), $deniedItems)) $foundItem->setActive(self::ITEM_DENIED);

                    $em->persist($foundItem);
                }

                $em->flush();
            }



            $result = new JsonModel (array(
                'approved' => $approvedDenied,
                'message' => 'success'
            ));
            return $result;
    }

    }

}
