<?php
namespace UserProfile\Validator;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Class ProfileSettingsValidator
 * @package UserProfile\Validator
 */
class ProfileSettingsValidator implements InputFilterAwareInterface
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

            $inputFilter->add(
                $factory->createInput(
                    [
                        'name' => 'nickname',
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
                                    'pattern' => '/^[a-zA-Z0-9-_ ]+$/',
                                    'message' => "Can contain only small letters,
                                            numbers dash, underline or space"
                                )
                            )
                        ),
                    ]
                )
            );
            $inputFilter->add(
                $factory->createInput(
                    [
                        'name' => 'phone',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'Regex',
                                'options' => array(
                                    'pattern' => '/^(?:\((\+?\d+)?\)|\+?\d+) ?\d*(-?\d{2,3} ?){0,4}$/',
                                    'message' => "Incorect phone number"
                                )
                            )
                        ),
                    ]
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    [
                        'name' => 'country',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                            array('name' => 'StringToLower'),
                        ),
                        'country' => array(
                            array(
                                'name' => 'Regex',
                                'options' => array(
                                    'pattern' => '/[ \pL]*/u',
                                    'message' => "Invalid country selection"
                                )
                            )
                        ),
                    ]
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    [
                        'name' => 'timezone',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                            array('name' => 'Int'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'Int',
                                'options' => array()
                            )
                        ),
                    ]
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    [
                        'name' => 'birthday',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'date',
                                'options' => array(
                                    'format' => 'm/d/Y',
                                    'locale' => 'us'
                                )
                            )
                        ),
                    ]
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    [
                        'name' => 'about_me',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'Regex',
                                'options' => array(
                                    'pattern' => '/[ \pL]*/u',
                                    'message' => "Invalid description"
                                )
                            )
                        ),
                    ]
                )
            );

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