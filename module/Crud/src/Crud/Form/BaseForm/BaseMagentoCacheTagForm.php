<?php
/**
 * This file is generated automatically for table "magento_cache_tag". Do not
 * change its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Form\BaseForm;

class BaseMagentoCacheTagForm extends \VisioCrudModeler\Form\AbstractForm
{

    public function __construct()
    {
        parent::__construct('magento_cache_tag');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'tag',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Tag',
            ),
        ));

        $this->add(array(
            'name' => 'cache_id',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'CacheId',
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

