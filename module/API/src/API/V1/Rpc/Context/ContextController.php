<?php
namespace API\V1\Rpc\Context;

use Application\Entity\Role;
use Application\View\Helper\Buttons;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class ContextController extends AbstractActionController
{

    public function contextAction()
    {
        return $this->{$this->params()->fromRoute('type')}();
    }

    public function menu()
    {

        $viewRenderer = $this->getServiceLocator()->get('ViewRenderer');

        /** @var \Application\View\Helper\Buttons $buttons */
        $buttons = $viewRenderer->user($this->params()->fromRoute('user'))->buttons()->setType(Buttons::TYPE_CONTEXT);

        if ($buttons->getUser()->getId() != $this->zfcUserAuthentication()->getIdentity()->getId()) {

            if ($buttons->getUser()->getRole() == Role::PERFORMER) {
                $items = [
                    'follow', 'favorite', 'tip', 'message', 'watch', 'watch-popup', 'call', 'sms', 'friend', 'profile',
                    'private'
                ];
            }
            else {
                $items = ['follow', 'favorite', 'message', 'friend', 'profile'];
            }

            if ($buttons->getUser()->hasUserRole()) {
                $items[] = 'kick';
            }

            $items = array_flip($items);
            foreach ($items as $index => &$item) {

                $value = json_decode($buttons->$index());

                if (empty($value)) {
                    unset($items[$index]);
                    continue;
                }

                $item = $value;

            }

            if ($buttons->getUser()->hasUserRole()) {
                $items["sep1"]    = "---------";
                $items["balance"] = [
                    'name' => 'Balance: $' . $this->getServiceLocator()->get('wallet')->getAmount($buttons->getUser()),
                ];
            }

        }
        else {
             $items['me'] = ['name' => 'This is me !'];
        }

        $items = array(
            'items' => $items,
            'userId' => $buttons->getUser()->getId()
        );

        $items = str_replace(
            ['"callback":"function', ';;;}"'],
            ['"callback":function', '}'],
            json_encode($items)
        );

        echo $this->params()->fromQuery('callback').'('.$items.');';
        exit;

    }

}
