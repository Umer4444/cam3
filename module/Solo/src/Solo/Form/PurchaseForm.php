<?php
namespace Solo\Form;

use Zend\Form\Form;

/**
 * Class loginForm
 * @package Solo\Form
 */
class PurchaseForm extends Form
{
    /**
     *
     * @param null $contentId
     * @param null $contentType
     *
     */
    public function __construct($contentId = null, $contentType = null)
    {
        parent::__construct('PurchaseForm');
        $this->setAttribute('method', 'post');
        $this->setAttribute('id', 'validation-form');
        $this->setAttribute('enctype', 'multipart/form-data');

        //contentID
        $this->add(array(
            'name' => 'contentId',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'hidden',
                'required' => true,
                'value' => $contentId
            ),
            'validators' => array(
                '\Zend\Validator\InArray' => array(
                    'haystack' => array($contentId),
                    'strict' => \Zend\Validator\InArray::COMPARE_STRICT)
            )
        ));
        //contentType
        $this->add(array(
            'name' => 'contentType',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'hidden',
                'required' => true,
                'value' => $contentType
            ),
            'validators' => array(
                '\Zend\Validator\InArray' => array(
                    'haystack' => array($contentId),
                    'strict' => \Zend\Validator\InArray::COMPARE_STRICT)
            )
        ));
        //submit
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'Submit',
                'value' => 'Buy',
                'class' => 'btn btn-purple'
            ),
        ));

    }

}