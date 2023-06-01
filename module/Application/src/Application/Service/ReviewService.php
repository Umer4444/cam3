<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
/**
 * Class ReviewService
 * @package Application\Service
 */
class ReviewService implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;
    /**
     * @var array
     */
    private $allowedTypes = array(
        'image',
        'photo',
        'video',
        'blog_post',
        'pledge',
        'album',
        'gallery',
    );


    /**
     * @param $params
     * @param $user
     * @return array
     */
    public function review($params, $user)
    {

        if (!isset($params['item_type'])
            || !isset($params['id'])
            || !isset($params['review'])
            || !isset($params['user_id'])
            || !in_array($params['item_type'], $this->allowedTypes)
        ) {
            return array('status' => 'error');
        }

        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        $response = array();

        $repo = new \Application\Entity\Reviews();

        $repo->setResourceType($params['item_type']);
        $repo->setResourceId($params['id']);
        $repo->setUser($user);
        $repo->setUserType('member');
        $repo->setReview($params['review']);
        $repo->setActive('0');
        $repo->setDate(time());

        $em->persist($repo);

        $em->flush();

        $response['message'] = "review saved! ";

        return $response;
    }


}