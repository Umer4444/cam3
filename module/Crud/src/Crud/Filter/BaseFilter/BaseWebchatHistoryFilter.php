<?php
/**
 * This file is generated automatically for table "webchat_history". Do not change
 * its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Filter\BaseFilter;

class BaseWebchatHistoryFilter extends \VisioCrudModeler\Filter\AbstractFilter
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
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => '0',
                            'max' => '32',
                        ),
                    ),
               )
        )));

        $inputFilter->add($factory->createInput(array(
               'name' => 'user_id',
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
               'name' => 'start',
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
               'name' => 'end',
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
               'name' => 'rating_surround',
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
               'name' => 'votes_surround',
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
               'name' => 'rating_appearance',
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
               'name' => 'votes_appearance',
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
               'name' => 'rating_sound',
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
               'name' => 'votes_sound',
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
               'name' => 'rating_light',
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
               'name' => 'votes_light',
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

