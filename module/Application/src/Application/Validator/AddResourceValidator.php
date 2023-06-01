<?php
namespace Application\Validator;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Class AddResourceValidator
 * @package Application\Validator
 */
class AddResourceValidator implements InputFilterAwareInterface
{
    protected $inputFilter;

    /**
     * @return InputFilter|InputFilterInterface
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput([
                'name' => 'key',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StringToLower'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^[a-zA-Z0-9-_]+$/',
                            'message' => "Can contain only small letters, numbers dash or underline"
                        )
                    )
                ),
            ]));
            $inputFilter->add($factory->createInput([
                'name' => 'label',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^[a-zA-Z0-9-_ \(\)\'\"]+$/',
                            'message' => "Can contain only small letters, space, numbers dash or underline"
                        )
                    )
                ),
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'resource',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StringToLower'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^[a-zA-Z0-9-_]+$/',
                            'message' => "Can contain only small letters, numbers dash or underline"
                        )
                    )
                ),
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'group',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StringToLower'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^[a-zA-Z0-9-_]+$/',
                            'message' => "Can contain only small letters, numbers dash or underline"
                        )
                    )
                ),
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'entity',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^[a-zA-Z0-9-_ ]+$/',
                            'message' => "Can contain only small letters, numbers dash or underline"
                        )
                    )
                ),
            ]));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    /**
     * @param InputFilterInterface $inputFilter
     * @return void|InputFilterAwareInterface
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
} 