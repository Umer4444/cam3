<?php

namespace Crud\Controller;

use Application\Entity\Role;
use PerfectWeb\Core\Traits\EntityManager;

class PhotoController extends Base\BasePhotoController
{

    use EntityManager;

    public $entity = '\\Images\\Entity\\Photo';

    /**
     * @inheritdoc
     */
    public function ajaxListAction()
    {

        /* @var $queryBuilder \Doctrine\ORM\QueryBuilder */
        $queryBuilder = $this->getEntityManager()->getRepository($this->entity)->createQueryBuilder('r');

        $queryBuilder->orderBy('r.id', 'desc');

        if (
            !in_array(
                $this->zfcUserAuthentication()->getIdentity()->getRole(),
                [Role::SUPER_ADMIN, Role::ADMIN]
            )
        ) {
            $queryBuilder->where(
                $queryBuilder->expr()->eq('r.user', $this->zfcUserAuthentication()->getIdentity()->getId())
            );
        }

        $table = $this->getServiceLocator()->get('\Crud\Grid\PhotoGrid');
        $table->setSource($queryBuilder)->setParamAdapter($this->getRequest()->getPost());

        return $this->htmlResponse($table->render());
    }

}

