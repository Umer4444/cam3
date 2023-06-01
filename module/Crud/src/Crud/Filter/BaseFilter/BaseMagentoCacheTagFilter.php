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


namespace Crud\Filter\BaseFilter;

class BaseMagentoCacheTagFilter extends \VisioCrudModeler\Filter\AbstractFilter
{

    public function __construct()
    {
        $inputFilter = $this->getInputFilter();
        $factory = $this->getInputFactory();

        $inputFilter->add($factory->createInput(array(
               'name' => 'tag',
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
                            'max' => '100',
                        ),
                    ),
               )
        )));

        $inputFilter->add($factory->createInput(array(
               'name' => 'cache_id',
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
                            'max' => '200',
                        ),
                    ),
               )
        )));
    }


}

