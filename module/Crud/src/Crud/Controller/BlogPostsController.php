<?php

namespace Crud\Controller;

use Application\Entity\Role;

class BlogPostsController extends Base\BaseBlogPostsController
{

    /**
     * @inheritdoc
     */
    public function ajaxListAction()
    {
        $em = $this->getServiceLocator()->get("em");

        /* @var $queryBuilder \Doctrine\ORM\QueryBuilder */
        $queryBuilder = $em->createQueryBuilder();

        $queryBuilder->add("select", "r")->add("from", $this->entity.' r');

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

        $table = $this->getServiceLocator()->get('\Crud\Grid\BlogPostsGrid');
        $table->setSource($queryBuilder)->setParamAdapter($this->getRequest()->getPost());

        return $this->htmlResponse($table->render());
    }

}

