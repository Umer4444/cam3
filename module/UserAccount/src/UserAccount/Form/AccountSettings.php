<?php
namespace UserAccount\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Class AccountSettings
 * @package UserAccount\Form
 */
class AccountSettings extends Form
{
    /**
     * @param null $profileSettings
     * @param array $user
     * @throws Exception
     */
    public function __construct($profileSettings = null, $user)
    {
        //if (!$profileSettings) throw new Exception('No profile settings provided');

        parent::__construct('account-settings-form');

        $addressArray = [];

        // Address entity no longer exists!!!

        /*
        $addressRepo = $entityManager->getRepository('Application\Entity\Address');

        foreach ($user->getAddressResources() as $address) {

            $addressArray[$address->getAddressId()] = $address->__toString();
        }
        */

        $addressArray[$user->getId()] =$user->getAddress();

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');

        $orderedSettings = [];
        foreach ($profileSettings as $setting) {
            $orderedSettings[$setting['key']] = $setting;
        }

        unset($profileSettings);

        $field = 'billing_first_name';
        $this->add(array(
            'name' => $field,
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'id' => $field,
                'value' => (isset($orderedSettings[$field]['value']) ? $orderedSettings[$field]['value'] : ''),
                //'placeholder' => 'Setting key',
                //'required' => 'required',
            ),
            'options' => array(
                'label' => (isset($orderedSettings[$field]['label']) ? $orderedSettings[$field]['label'] : ''),
            ),
        ));

        $field = 'billing_last_name';
        $this->add(array(
            'name' => $field,
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'id' => $field,
                'value' => (isset($orderedSettings[$field]['value']) ? $orderedSettings[$field]['value'] : ''),
                //'placeholder' => 'Setting key',
                //'required' => 'required',
            ),
            'options' => array(
                'label' => (isset($orderedSettings[$field]['label']) ? $orderedSettings[$field]['label'] : ''),
            ),
        ));

        $field = 'default_billing_address';
        $this->add(array(
            'name' => $field,
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => $field,
                'required' => 'required',
                'value' => $orderedSettings[$field]['value'],
                // 'value' => 1
            ),
            'options' => array(
                'label' => $orderedSettings[$field]['label'],
                'empty_option' => 'Please choose your billing address',
                'value_options' => $addressArray,

            ),
        ));

        $field = 'default_shiping_address';
        $this->add(array(
            'name' => $field,
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => $field,
                'required' => 'required',
                'value' => $orderedSettings[$field]['value'],
                // 'value' => 1
            ),
            'options' => array(
                'label' => $orderedSettings[$field]['label'],
                'empty_option' => 'Please choose your shipping address',
                'value_options' => $addressArray,

            ),
        ));

        $field = 'submit';
        $this->add(array(
            'name' => $field,
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'id' => $field,
                // 'required' => 'required',
                'value' => 'Save',
                'class' => 'btn btn-magenta'
            ),
            'options' => array(
                'label' => '',
            ),
        ));

        // p($orderedSettings);
        // exit;
    }
}