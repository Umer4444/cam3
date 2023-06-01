<?php

namespace PerfectWeb\Core\Controller;

use Zend\View\Model\ViewModel;
use CgmConfigAdmin\Controller\ConfigOptionsController;

/**
 * Class ConfigController
 * @package PerfectWeb\Core\Controller
 */
class ConfigController extends ConfigOptionsController
{

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {

        $service = $this->getConfigAdminService();

        if ($this->request->isPost()) {
            $config = $this->request->getPost();
            $successful = false;
            if (!empty($config['preview'])) {
                if ($service->previewConfigValues($config)) {
                    $message = '<strong>Ready to preview</strong> ';
                    $message .= 'You may navigate the site to test your changes. ';
                    $message .= '<div><em>The changes will not be made permanent until saved.</em></div>';
                    $message = array('message' => $message, 'type' => 'info');
                    $successful = true;
                }

            } else if (!empty($config['reset'])) {
                $service->resetConfigValues();
                $message = '<strong>Preview Settings have been reset</strong> ';
                $message = array('message' => $message);
                $successful = true;

            } else if (!empty($config['save'])) {
                if ($service->saveConfigValues($config)) {
                    $message = '<strong>Settings have been saved</strong> ';
                    $message = array('message' => $message, 'type' => 'success');
                    $successful = true;
                }
            }

            if ($successful) {
                $this->flashMessenger()
                    ->setNamespace('cgmconfigadmin')
                    ->addMessage($message);
                return $this->redirect()->toRoute(null, $this->params()->fromRoute());
            }
        }

        $view = new ViewModel();
        $view->setTemplate('perfect-web/config/index');
        $view->setVariables([
            'form' => $this->getConfigAdminService()->getConfigOptionsForm($this->params()->fromRoute('group')),
        ]);

        return $view;

    }

    public function getConfigAdminService($context = 'cfg')
    {
        if (!$this->configAdminService) {
            $this->configAdminService = $this->getServiceLocator()->get($context);
        }
        return $this->configAdminService;
    }

}
