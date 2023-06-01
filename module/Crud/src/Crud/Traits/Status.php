<?php

namespace Crud\Traits;

use PerfectWeb\Core\Utils\Status as UtilsStatus;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

trait Status
{

    function onStatus()
    {
        $this->getHeader("status")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return UtilsStatus::getFrom($record->getStatus());
            }
        ));
    }

    function addFormStatus()
    {

        $specs = array(
            'name' => 'status',
            'type'  => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Status',
                'value_options' => self::getStatusValues(),
            ),
        );

        if ($this instanceof \Zend\Form\FormInterface) {
            if (
                method_exists($this, 'getServiceLocator') &&
                !$this->getServiceLocator()->get('user')->getIdentity()->hasModeratorRole()
            ) {
                return $specs;
            }
            return $this->add($specs);
        }

        return $specs;

    }

    /**
     * @deprecated
     * @return array
     * @throws \Exception
     */
    static function getStatusValues()
    {
        return \PerfectWeb\Core\Utils\Status::getStatusValues();
    }

}