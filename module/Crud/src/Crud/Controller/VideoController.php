<?php

namespace Crud\Controller;

class VideoController extends Base\BaseVideoController
{
    public $entity = \Videos\Entity\Video::class;

    /**
     * handles updating entity data
     */
    public function updateAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toUrl('/admin/crud/video/list');
        }

        $form = $this->getServiceLocator()->get('\Crud\Form\VideoForm');
        $filter =$this->getServiceLocator()->get('\Crud\Filter\VideoFilter');
        $form->setInputFilter($filter->getInputFilter());

        $em = $this->getServiceLocator()->get("em");
        $row = $em->find($this->entity, $id);
        $form->setHydrator(new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($em));
        $row->setTags(implode(',', $row->getTags()));
        $form->bind($row);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $em->flush();
                return $this->redirect()->toUrl('/admin/crud/video/list');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

}

