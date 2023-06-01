<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;
use PerfectWeb\Core\Traits;

/**
 * Class BadgesHelper
 * @package Application\View\Helper
 */
class BadgesHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    const VIEWS = 1000;

    const BADGE_VIEWS = '/assets/images/badges/Most-Watched.png';

    /**
     * @param $id
     * @param $object
     * @param $type
     * @return mixed
     */
    use Traits\EntityManager;

    public function __invoke($object, $id, $type = 'views')
    {
        if (!is_object($object)) {
            throw new \Exception($object . ' is not an object');
        }

        $badge = $this->getEntityManager()
            ->getRepository('Interactions\Entity\Interaction')
            ->findOneBy(
                ['entity' => get_class($object), 'entityReference' => $id],
                [$type =>'DESC']
            );

        if (empty($badge)) {
            return $badge;
        }

        switch ($type){

            case 'views':

                if ($badge->getViews() > self::VIEWS) {
                    $badgeImage = self::BADGE_VIEWS;
                }

                break;

        }

        return $badgeImage;

    }

}