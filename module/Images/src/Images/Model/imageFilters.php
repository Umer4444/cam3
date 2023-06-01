<?php
namespace Images\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class imageFilters implements InputFilterAwareInterface
{
    public $id;
    public $name;
    public $description;
    public $fileupload;
    protected $inputFilter; // <-- Add this variable

    // Add content to these methods:

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add(
                $factory->createInput(array(
                    'name' => 'extension',
                    'required' => false,
                ))
            );

            $inputFilter->add(array(
                'name' => 'name',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Regex',

                        'options' => array(

                            'pattern' => '/^[a-zA-Z0-9- \.\+]+$/',
                            'messages' => array(
                                \Zend\Validator\Regex::INVALID
                                => "Invalid type given. String, integer or float expected",
                                \Zend\Validator\Regex::NOT_MATCH => "You cannot use special characters",
                                \Zend\Validator\Regex::ERROROUS
                                => "There was an internal error while using the pattern",

                            ),
                        ),

                    ),
                ),

            ));

            $inputFilter->add(array(
                'name' => 'description',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),

            ));
            $inputFilter->add(array(
                'name' => 'extension',
                'required' => true,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
}