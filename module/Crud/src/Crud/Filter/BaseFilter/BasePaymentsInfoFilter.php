<?php
/**
 * This file is generated automatically for table "payments_info". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Filter\BaseFilter;

class BasePaymentsInfoFilter extends \VisioCrudModeler\Filter\AbstractFilter
{

    public function __construct()
    {
        $inputFilter = $this->getInputFilter();
        $factory = $this->getInputFactory();

        $inputFilter->add($factory->createInput(array(
               'name' => 'id',
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
               'name' => 'user_type',
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
                            'max' => '255',
                        ),
                    ),
               )
        )));

        $inputFilter->add($factory->createInput(array(
               'name' => 'payment_method',
               'required' => true,
               'filters' => array(
                   array('name' => 'StripTags'),
                   array('name' => 'StringTrim')
               ),
               'validators' => array(
                   array(
                       'name' => 'StringLength',
                       'options' => array(
                           'encoding' => 'UTF-8',
                           'min' => '1',
                           'max' => '256'
                       )
                   ),
               )
        )));

        $inputFilter->add($factory->createInput(array(
               'name' => 'info',
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
    }


}

