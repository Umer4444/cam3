<?php

namespace Crud\Controller\Base;

class BaseUserAddressesController extends \Crud\Controller\ActionController
{

    public $entity = '\\Application\\Entity\\UserAddresses';

    /**
     * handles adding new entry
     */
    public function createAction()
    {
        $form = $this->getServiceLocator()->get('\Crud\Form\UserAddressesForm');
        $request = $this->getRequest();

        $filter = $this->getServiceLocator()->get('\Crud\Filter\UserAddressesFilter');
        $form->setInputFilter($filter->getInputFilter());

        $row = new $this->entity();
        $em = $this->getServiceLocator()->get("em");
        $form->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($em));
        $form->setObject($row);
        $form->bind($row);

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $em->persist($row);
                $em->flush();

                // Redirect to list of albums
                return $this->redirect()->toUrl('/admin/crud/user-addresses/list');
            }
        }
        return array('form' => $form);
    }

    /**
     * handles sending results to ajax table
     */
    public function listAction()
    {
    }

    /**
     * handles sending results to ajax table
     */
    public function ajaxListAction()
    {
        $em = $this->getServiceLocator()->get("em");
        $queryBuilder = $em->createQueryBuilder();

        $queryBuilder->add("select", "r")->add("from", $this->entity.' r')->orderBy('r.id', 'DESC');

        $table = $this->getServiceLocator()->get('\Crud\Grid\UserAddressesGrid');
        $table->setSource($queryBuilder)->setParamAdapter($this->getRequest()->getPost());

        return $this->htmlResponse($table->render());
    }

    /**
     * handles updating entity data
     */
    public function updateAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toUrl('/admin/crud/user-addresses/list');
        }

        $form = $this->getServiceLocator()->get('\Crud\Form\UserAddressesForm');
        $filter =$this->getServiceLocator()->get('\Crud\Filter\UserAddressesFilter');
        $form->setInputFilter($filter->getInputFilter());

        $em = $this->getServiceLocator()->get("em");
        $row = $em->find($this->entity, $id);
        $form->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($em));
        $form->bind($row);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $em->flush();
                return $this->redirect()->toUrl('/admin/crud/user-addresses/list');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    /**
     * handles deleting single entity
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toUrl('/admin/crud/user-addresses/list');
        }
        $em = $this->getServiceLocator()->get("em");
        $em->remove($em->find($this->entity, $id));
        $em->flush();

        return $this->redirect()->toUrl('/admin/crud/user-addresses/list');
    }

    /**
     * Return response as html
     */
    public function htmlResponse($html)
    {
        $response = $this->getResponse()
        ->setStatusCode(200)
        ->setContent($html);
        return $response;
    }


}

