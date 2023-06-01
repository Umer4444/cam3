<?php

namespace Application\Controller\Admin;

use Application\Entity\WebchatSessions;
use Zend\Mvc\Controller\AbstractActionController;

use Crud\Traits as CrudTraits;
use PerfectWeb\Core\Traits;

/**
 * Class PerformerAdminController
 * @package Application\Controller\Admin
 */
class PerformerAdminController extends AbstractActionController
{

    use CrudTraits\Type;
    use Traits\Ensure;

    public function manageProfilePageAction()
    {}

    public function recordAction()
    {

        $specs = [];

        if (!$this->getRequest()->isPost()) {

            $spec = $this->getFormType(\Videos\Entity\Video::class);
            $spec['options']['label'] = 'Select Type';

            $specs = [
                'elements' => array(
                    [
                        'spec' => $spec
                    ],
                    [
                        'spec' => array(
                            'name' => 'title',
                            'type'  => 'Zend\Form\Element\Text',
                            'attributes' => array(
                                'class' => 'form-control',
                                'placeholder' => 'Clip title',
                                'value' => 'recording '.date('m/d/Y'),
                            ),
                            'options' => [
                                'label' => 'Title'
                            ]
                        )
                    ],
                    [
                        'spec' => array(
                            'name' => 'submit',
                            'type'  => 'Zend\Form\Element\Submit',
                            'attributes' => array(
                                'class' => 'btn btn-success',
                                'value' => 'Start recording'
                            ),
                        )
                    ],
                )
            ];

        }
        else {
            $this->flashMessenger()->clearMessages(\Videos\Entity\Video::class);
            $this->flashMessenger()->addMessage($this->params()->fromPost('type'), \Videos\Entity\Video::class);
            $this->flashMessenger()->addMessage($this->params()->fromPost('title'), \Videos\Entity\Video::class);
        }

        return [
            'form' => (new \Zend\Form\Factory())->createForm($specs),
            'title' => $this->params()->fromPost('title'),
            'type' => $this->objectToName($this->params()->fromPost('type')),
        ];

    }

    public function broadcastAction()
    {

        $chatSession = new WebchatSessions();

        $user = $this->zfcUserAuthentication()->getIdentity();

        if ($this->params()->fromRoute('type') == 'private') {

            if ($user->getCurrentChatSession()) {
                $user->getCurrentChatSession()->setEndDate(new \DateTime());
            }

            $user->setBroadcastType('duplex')
                 ->setBroadcastMode('private')
                 ->addChatSession(
                     $chatSession->setRoomStatus('private session')
                                 ->setSession($this->params()->fromRoute('session'))
                                 ->setStarter($this->params()->fromRoute('starter') ?
                                                  $this->ensureUser($this->params()->fromRoute('starter')) : null
                                 )
                 );

            $this->getEntityManager()->flush();

            return $this->redirect()->toRoute('zfcadmin/broadcast');

        }

        if ($this->getRequest()->isPost()) {

            if ($this->params()->fromPost('stopBroadcast')) {

                $user->setBroadcastMode(null)->setBroadcastType(null);

                if ($user->getCurrentChatSession()) {
                    $user->getCurrentChatSession()->setEndDate(new \DateTime());
                }

            }
            else {

                $chatSession->setRoomStatus($this->params()->fromPost('status'));

                $user->setBroadcastMode($this->params()->fromPost('broadcastMode'))
                     ->setNumberOfCameras((int)$this->params()->fromPost('cameras', 1))
                     ->setBroadcastType($this->params()->fromPost('broadcastType'))
                     ->addChatSession($chatSession);
            }

            $this->getEntityManager()->flush();

            return $this->prg();
        }

    }

}