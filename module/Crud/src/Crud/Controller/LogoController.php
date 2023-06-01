<?php

namespace Crud\Controller;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Gedmo\Uploadable\UploadableListener;

class LogoController extends Base\BaseScheduledMediaController
{

    public $entity = '\\Application\\Entity\\Logo';

    /**
     * @inheritdoc
     */
    public function createAction()
    {
        $form = $this->getServiceLocator()->get('\Crud\Form\ScheduledMediaForm');
        $request = $this->getRequest();

        $filter = new \Crud\Filter\ScheduledMediaFilter();
        $form->setInputFilter($filter->getInputFilter());

        if ($request->isPost()) {

            $row = new \Application\Entity\Logo();

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $em = $this->getServiceLocator()->get("em");
                $form->setHydrator(new DoctrineObject($em));
                $form->setObject($row);
                $row->setUser($this->zfcUserAuthentication()->getIdentity());

                if ($request->getFiles('filename')['name']) {
                    $listener = new UploadableListener();
                    $listener->addEntityFileInfo($row, $request->getFiles('filename'));
                    $em->getEventManager()->addEventSubscriber($listener);
                }

                $em->persist($row);
                $em->flush();

                // Redirect to list of albums
                return $this->redirect()->toUrl('/admin/crud/logo/list');
            }

        }
        return array('form' => $form);
    }

    /**
     * @inheritdoc
     */
    public function updateAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toUrl('/admin/crud/logo/list');
        }

        $em = $this->getServiceLocator()->get("em");
        $row = $em->find('\Application\Entity\Logo', $id);
        $form = $this->getServiceLocator()->get('\Crud\Form\ScheduledMediaForm');
        $hydrator = new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($em);
        $dateTimeStrategy = new \VisioCrudModeler\Hydrator\Strategy\DateTimeStrategy();
        $form->setHydrator($hydrator->addStrategy('start', $dateTimeStrategy)->addStrategy('end', $dateTimeStrategy));
        $form->bind($row);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $filter = new \Crud\Filter\BaseFilter\BaseScheduledMediaFilter();
            $form->setInputFilter($filter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $em->flush();
                return $this->redirect()->toUrl('/admin/crud/logo/list');
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
            return $this->redirect()->toUrl('/admin/crud/logo/list');
        }
        $em = $this->getServiceLocator()->get("em");
        $em->remove($em->find($this->entity, $id));
        $em->flush();

        return $this->redirect()->toUrl('/admin/crud/logo/list');
    }

    /**
     * handles sending results to ajax table
     */
    public function ajaxListAction()
    {
        $em = $this->getServiceLocator()->get("em");
        $queryBuilder = $em->createQueryBuilder();

        $queryBuilder->add("select", "r")->add("from", $this->entity.' r');

        $table = $this->getServiceLocator()->get('\Crud\Grid\LogoGrid');
        $table->setSource($queryBuilder)->setParamAdapter($this->getRequest()->getPost());

        return $this->htmlResponse($table->render());
    }

}

