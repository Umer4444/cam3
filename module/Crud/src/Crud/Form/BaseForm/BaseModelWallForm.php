<?php
/**
 * This file is generated automatically for table "model_wall". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Form\BaseForm;

class BaseModelWallForm extends \VisioCrudModeler\Form\AbstractForm
{

    public function __construct()
    {
        parent::__construct('model_wall');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id_wall',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'IdWall',
            ),
        ));

        $this->add(array(
            'name' => 'id_owner',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'IdOwner',
            ),
        ));

        $this->add(array(
            'name' => 'id_user',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'IdUser',
            ),
        ));

        $this->add(array(
            'name' => 'user_type',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'UserType',
            ),
        ));

        $this->add(array(
            'name' => 'message',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Message',
            ),
        ));

        $this->add(array(
            'name' => 'added',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Added',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'form-control btn-success',
                'style' => 'width: 50%'
            ),
        ), ['priority' => -1000]);
    }


}

