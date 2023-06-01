<?php

namespace Images\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
/**
 * Class UserIndexController
 * @package Images\Controller
 */
class UserIndexController extends AbstractActionController
{
    /**
     * @return ViewModel
     */
    public function imagesPreviewAction()
    {

        // $session->random = 0;

        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $serviceLocator = $this->getServiceLocator();
        $imagesRepo = $entityManager->getRepository('Images\Entity\Images');
        $request = $this->getRequest();
        $url = $request->getPost('nextUrl');
        if ($this->getRequest()->isXmlHttpRequest()) { // If it's ajax call


            $random = $request->getPost('random');


            if ($random == 1) {

                $_SESSION['random'] = 1;

            } else {

                $_SESSION['random'] = 0;

            }

            $response = $this->getResponse();
            $response->setStatusCode(301)->getHeaders()
                ->addHeaders(array('redirectURL' => '/image/' . $url));
            return $response;
        }
        //p($_SESSION['random']);die();

        if ($_SESSION['random'] == 1) {

            $imagesRepo = $entityManager->getRepository('Images\Entity\Images');
            $images = $imagesRepo->findAll();
            shuffle($images);
            $nextUrl = $images[0];
            $thisUrl = $this->params()->fromRoute('slug');
            $request = $this->getRequest();
            $url = $request->getPost('nextUrl');
            if ($url) {

                $image = $imagesRepo->findOneBy(array('slug' => $url));

            } else {

                $image = $imagesRepo->findOneBy(array('slug' => $thisUrl));

            }

            $random = 1;

        } else {

            $slug = $this->params()->fromRoute('slug');
            $image = $imagesRepo->findOneBy(array('slug' => $slug));
            $images = $imagesRepo->findAll();
            shuffle($images);
            $nextUrl = $images[0];
            $random = 0;

        }

        $ratingService = $serviceLocator->get('wtrating.service');

        return new ViewModel(array(
            'random' => $random,
            'nextUrl' => $nextUrl,
            'image' => $image,
            'rating' => $ratingService

        ));
    }
}
