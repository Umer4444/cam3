<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use PerfectWeb\Core\Traits;

/**
 * Class PhotosHelper
 * @package Application\View\Helper
 */
class PhotosHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use Traits\EntityManager;
    /**
     * @param $id
     * @param $entity
     * @param $type
     * @return mixed
     */
    public function __invoke($id, $type)
    {

        $photo = $this->getEntityManager()
                      ->getRepository('Images\Entity\Photo')
                      ->findOneBy(['type' => $type, 'user' => $id, 'status' => 1]);

        if(!is_object($photo)) $image = '/images/no-picture.png';
        else $image = $photo->getFilename();

        return $image;

    }

}