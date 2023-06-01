<?php
/**
 * This file is generated automatically for table "pledge_perk". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Filter\BaseFilter;

class BasePledgePerkFilter extends \VisioCrudModeler\Filter\AbstractFilter
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
               'name' => 'id_pledge',
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
               'name' => 'description',
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
               'name' => 'amount',
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
               'name' => 'user_limit',
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
               'name' => 'estimated_delivery',
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
               'name' => 'status',
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
               'name' => 'title',
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
               'name' => 'quantity',
               'required' => true,
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
               'name' => 'shipping',
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
               'name' => 'id_photo',
               'required' => true,
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

