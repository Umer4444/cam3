<?php
/**
 * This file is generated automatically for table "model_to_categories". Do not
 * change its contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Filter\BaseFilter;

class BaseModelToCategoriesFilter extends \VisioCrudModeler\Filter\AbstractFilter
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
               'name' => 'id_category',
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
               'name' => 'sort',
               'required' => false,
               'filters' => array(
                   array('name' => 'StripTags'),
                   array('name' => 'StringTrim')
               ),
               'validators' => array(
                    array (
                        'name' => 'Digits',
                    ),
               )
        )));
    }


}

