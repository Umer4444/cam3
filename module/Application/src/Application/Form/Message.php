<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
/**
 * Please be careful, this form can not be used while no user is logged in
 * Class Message
 * @package Application\Form
 */
class Message extends Form implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __construct($sm)
    {
        parent::__construct();

        $this->setServiceLocator($sm);
        /**
         * @var /Application/Entity/User $sender
         */
        $sender = $this->getServiceLocator()->get('zfcuser_auth_service')->getIdentity();

        $chips = $sender->getCredit();
        $this->setAttribute('method', 'post');
        $this->setAttribute('id', 'validation-form');
        $this->setAttribute('enctype', 'multipart/form-data');

        //contentID
        $this->add(array(
            'name' => 'sender',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'hidden',
                'required' => true,
                'value' => $sender->getId(),

            ),
            'validators' => array(
                '\Zend\Validator\InArray' => array(
                    'haystack' => array($sender->getId()),
                    'strict' => \Zend\Validator\InArray::COMPARE_STRICT)
            )
        ));

         //sendToId
        $this->add(array(
            'name' => 'sendToId',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'hidden',
                'required' => true,
                'id' => 'sendtoid'

            ),
        ));
        //sendTo
        $this->add(array(
            'name' => 'sendTo',
            'type' => 'Text',
            'attributes' => array(
                'required' => true,
                'placeholder' => 'Send To',
                'id' => 'sendto'
            ),

        ));
        //subject
        $this->add(array(
            'name' => 'subject',
            'type' => 'Text',
            'attributes' => array(
                'required' => true,
                'placeholder' => 'Subject'
            ),

        ));
        $this->add(array(
            'name' => 'message',
            'type' => 'Textarea',
            'attributes' => array(
                'required' => true,
                'placeholder' => 'Message'
            ),

        ));
        $this->add(array(
            'name' => 'tip',
            'type' => 'number',
            'attributes' => array(
                'required' => false,
                'placeholder' => 'Tip',
                'id' => 'tip',
                'step' => 1,
                'min' => 0,
                'max' => $chips,
                'onkeypress' => 'return isNumeric(event);',

            ),

            'filters' => array(
                array('name' => 'Int'),
            ),
            'validators' => array(
                array(
                    'name' => 'Between',
                    'options' => array(
                        'min' => 0,
                        'max' => $chips,
                    ),
                ),
            ),
        ));
        //submit
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'Submit',
                'value' => 'Send',
                'class' => 'btn btn-purple'
            ),
        ));

    }

}