<?php

namespace Crud\Form;

use Application\Entity\Role;

class RulesForm extends BaseForm\BaseRulesForm
{

    public function __construct()
    {

        parent::__construct();

        $this->remove('id');

        $this->remove('fine_text');
        $this->add(array(
            'name' => 'fineText',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'FineText',
            ),
        ));

        $this->remove('free_chat');
        $this->add(array(
            'name' => 'freeChat',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Free Chat',
                'value_options' => array(
                    'yes' => 'Yes',
                    'yes*' => 'Yes*',
                    'yes**' => 'Yes**',
                    'yes***' => 'Yes***',
                    'no' => 'No',
                ),
            )
        ));


        $this->remove('videos');
        $this->add(array(
            'name' => 'videos',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Videos',
                'value_options' => array(
                    'yes' => 'Yes',
                    'yes*' => 'Yes*',
                    'yes**' => 'Yes**',
                    'yes***' => 'Yes***',
                    'no' => 'No',
                ),
            )
        ));

        $this->remove('photos');
        $this->add(array(
            'name' => 'photos',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Photos',
                'value_options' => array(
                    'yes' => 'Yes',
                    'yes*' => 'Yes*',
                    'yes**' => 'Yes**',
                    'yes***' => 'Yes***',
                    'no' => 'No',
                ),
            )
        ));

        $this->remove('paid_chat');
        $this->add(array(
            'name' => 'paidChat',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Paid Chat',
                'value_options' => array(
                    'yes' => 'Yes',
                    'yes*' => 'Yes*',
                    'yes**' => 'Yes**',
                    'yes***' => 'Yes***',
                    'no' => 'No',
                ),
            )
        ));

        $this->remove('type');
        $this->add(array(
            'name' => 'type',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Type',
                'value_options' => array(
                    Role::USER => Role::USER,
                    Role::PERFORMER => Role::PERFORMER,
                    Role::STUDIO => Role::STUDIO,
                    Role::STUDIO_MANAGER => Role::STUDIO_MANAGER,
                    Role::MODERATOR => Role::MODERATOR
                ),
            )
        ));

    }

}