<?php

namespace Application\Controller;

use PerfectWeb\Core\Entity\ResourceValue;
use Zend\Filter\Word\DashToCamelCase;
use Zend\View\Model\ViewModel;
use PerfectWeb\Core\Controller\ConfigController as PerfectWebConfigController;

/**
 * Class ConfigController
 * @package PerfectWeb\Core\Controller
 */
class ConfigController extends PerfectWebConfigController
{

    public function indexAction()
    {

        $action = $this->params()->fromRoute('group');
        $service = $this->getConfigAdminService()->setLimitToGroup($action);

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

            switch (true) {
                case count($this->getConfigAdminService()->getLimitToGroup()) > 1:
                    $action = 'multiple';
                break;
                case !empty($this->params()->fromRoute('proxy-action')):
                    $action = $this->params()->fromRoute('proxy-action');
                break;
                case $this->params()->fromRoute('action') != 'index':
                    $action = $this->params()->fromRoute('action');
                break;
            }

            if ($successful) {

                $this->flashMessenger()->setNamespace('cgmconfigadmin')->addMessage($message);

                return $this->redirect()->toRoute(
                    $this->params()->fromRoute('route'),
                    array_merge($this->params()->fromRoute(), ['action' => $action])
                );

            }

        }

        $view = new ViewModel();
        $view->setTemplate('perfect-web/config/index');

        //@TODO - fix error
        $view->setVariables([
            'form' => $service->getConfigOptionsForm(),
        ]);

        return $view;

    }

    function connectAction()
    {
        return $this->doForward();
    }

    function sessionAction()
    {
        return $this->doForward();
    }

    function playAction()
    {
        return $this->doForward();
    }

    function priceAction()
    {
        return $this->doForward();
    }

    function broadcastAction()
    {
        return $this->doForward();
    }

    function chatNotifiersAction()
    {
        return $this->doForward();
    }

    function payoutTypeAction()
    {
        return $this->doForward();
    }

    function defaultPayoutAction()
    {
        $this->getConfigAdminService('([a-z]+).cfg')
             ->getEventManager()
             ->attach('saveConfigValues', function($e) {

                 /** @var \Zend\EventManager\Event $e */
                 $e->stopPropagation(true);

                 /** @var ResourceValue $configValue */
                 foreach ($e->getParam('configValues') as $configValue) {
                     $optionConfig = $configValue->getResource()->getOptionConfig();
                     $optionConfig['default_value'] = $configValue->getValue();
                     $configValue->getResource()->setOptionConfig($optionConfig);
                 }

                 $e->getTarget()->getEntityManager()->flush();

             });

        return $this->doForward(
            [
                'group' => 'payout',
                'route' => 'zfcadmin/config',
                'proxy-action' => 'default-payout'
            ]
        );
    }

    private function doForward($params = [])
    {
        return $this->forward()->dispatch(
            self::class,
            array_merge(['action' => 'index', 'group' => $this->params()->fromRoute('action')], $params)
        );
    }

    function multipleAction()
    {
        /* @var $authorize \BjyAuthorize\Service\Authorize */
        $controller    = $this->getServiceLocator()->get('BjyAuthorize\Guard\Controller');
        $authorize = $this->getServiceLocator()->get('BjyAuthorize\Service\Authorize');
        $validGroups = [];

        foreach (explode(',', $this->params()->fromRoute('group')) as $group) {
            if (empty($group)) {
                continue;
            }
            if (!$authorize->isAllowed($controller->getResourceName(self::class, $group))) {
                throw new \BjyAuthorize\Exception\UnAuthorizedException();
            }
            $validGroups[] = $group;
        }

        $group = implode(',', $validGroups);

        return $this->forward()->dispatch(
            self::class,
            [
                'action' => (new DashToCamelCase())->filter($group) ? : 'index',
                'group' => $group,
                'route' => $this->getEvent()->getRouteMatch()->getMatchedRouteName()
            ]
        );

    }

}