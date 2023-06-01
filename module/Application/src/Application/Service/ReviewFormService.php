<?php
namespace Application\Service;

use Zend\View\Model\JsonModel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
/**
 * Class ReviewFormService
 * @package Application\Service
 */
class ReviewFormService implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;



    /**
     * function for getting entity for voting and setting check if voted
     *
     * @param mixed $type - object type
     * @param mixed $id - object id
     * @param mixed $action - action: vote or rate
     */
    public function reviewForm($params = null, $user = null)
    {

        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $form = new \Application\Form\AddReview('addReview', $entityManager); //initializing the form
        $reviewEntity = new \Application\Entity\Reviews();
        $form->bind($reviewEntity);
        $response = array();
        if (!$params) {

            return $form;

        } else {

            $data = array('review' => $params['review']);
            $reviewFilters = new \Application\Filter\ReviewFilters();
            $form->setInputFilter($reviewFilters->getInputFilter());
            $form->setValidationGroup('review');

            $form->setData($data);

            if ($form->isValid()) {

                $reviewEntity->setResourceType($params['item_type']);
                $reviewEntity->setResourceId($params['item']);
                $reviewEntity->setUser($user);
                $reviewEntity->setActive('0');
                $reviewEntity->setDate(time());
                $reviewEntity->setUserType('member');

                $entityManager->persist($reviewEntity);
                $entityManager->flush(); //adding values to database

                if (!$user->getUsername()) {
                    $username = 'Anonymous ';
                } else {

                    $username = $user->getUsername();
                }
                if (!$user->getAvatar()) {

                    $avatar = '/assets/themes/anakaliyah.com/assets/images/user_default.png';

                } else {

                    $avatar = $user->getAvatar();

                }

                $response = array(
                    'status' => 'success',
                    'message' => "Review added",
                    'review' => array(
                        'review' => $reviewEntity->getReview(),
                        'user' => $username,
                        'avatar' => $avatar,
                        'date' => $reviewEntity->getDate(),
                        'id' => $reviewEntity->getId()

                    )

                );

            } else {
                $response = array(
                    'status' => 'fail',
                    'message' => "Form invalid",
                    'errors' => $form->getMessages(),
                );

            }

        }

        return new JsonModel($response);
    }

}