<?php
/**
 * This file is generated automatically for table "model_user_notes". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Filter\BaseFilter;

class BaseModelUserNotesFilter extends \VisioCrudModeler\Filter\AbstractFilter
{

    public function __construct()
    {
        $inputFilter = $this->getInputFilter();
        $factory = $this->getInputFactory();

        $inputFilter->add($factory->createInput(array(
               'name' => 'id_model',
               'required' => false,
               'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
               'validators' => array(
                    array (
                        'name' => 'Digits',
                    ),
               )
        )));

        $inputFilter->add($factory->createInput(array(
               'name' => 'id_user',
               'required' => false,
               'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
               'validators' => array(
                    array (
                        'name' => 'Digits',
                    ),
               )
        )));

        $inputFilter->add($factory->createInput(array(
               'name' => 'notes',
               'required' => false,
               'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
               'validators' => array(
                    array (
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => '0',
                            'max' => '65535',
                        ),
                    ),
               )
        )));

        $inputFilter->add($factory->createInput(array(
               'name' => 'added',
               'required' => false,
               'filters' => array(
                    array('name' => 'StringTrim'),
                    array('name' => 'StripTags'),
                ),
               'validators' => array(
                    array (
                        'name' => 'Digits',
                    ),
               )
        )));
    }


}

