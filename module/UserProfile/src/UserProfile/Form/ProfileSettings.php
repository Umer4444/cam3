<?php
namespace UserProfile\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Class ProfileSettings
 * @package UserProfile\Form
 */
class ProfileSettings extends Form
{
    /**
     * @param null $profileSettings
     * @param null $entityManager
     * @throws \Exception
     */
    public function __construct($profileSettings = null, $entityManager = null)
    {

        /*
        if (!$profileSettings) throw new
        \Exception('No profile setings provided');
        */
        parent::__construct('profile-settings-form');

        $countryRepo = $entityManager
            ->getRepository('Application\Entity\Country');
        $countries = $countryRepo->findAll();

        $tzRepo = $entityManager
            ->getRepository('Application\Entity\Timezones');
        $timezones = $tzRepo->findAll();

        $countriesArray = array();

        foreach ($countries as $c) {
            $countriesArray[$c->getCountryCode()] = $c->getCountry();
        }

        $timezonesArray = array();

        foreach ($timezones as $tz) {
            $timezonesArray[$tz->getId()] = $tz->getName();
        }

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');

        $orderedSettings = array();
        foreach ($profileSettings as $setting) {
            $orderedSettings[$setting['key']] = $setting;
        }

        unset($profileSettings);

        $field = 'nickname';
        $this->add(
            array(
                'name' => $field,
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
                    'id' => $field,
                    'value' => $orderedSettings[$field]['value'],
                    //'placeholder' => 'Setting key',
                    //'required' => 'required',
                ),
                'options' => array(
                    'label' => $orderedSettings[$field]['label'],
                ),
            )
        );

        $field = 'phone';
        $this->add(
            array(
                'name' => $field,
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
                    'id' => $field,
                    'value' => $orderedSettings[$field]['value'],
                    //'placeholder' => 'Setting key',
                    //'required' => 'required',
                ),
                'options' => array(
                    'label' => $orderedSettings[$field]['label'],
                    'disable_inarray_validator' => true
                ),

            )
        );

        $field = 'country';
        $this->add(
            array(
                'name' => $field,
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => $field,
                    'value' => $orderedSettings[$field]['value'],
                    'required' => false
                    // 'value' => 1
                ),
                'options' => array(
                    'label' => $orderedSettings['country']['label'],
                    'empty_option' => 'Please choose your country',
                    'value_options' => $countriesArray,
                    'disable_inarray_validator' => true

                ),
            )
        );

        $field = 'timezone';
        $this->add(
            array(
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
                    'empty_option' => 'Please choose your timezone',
                    'value_options' => $timezonesArray,

                ),
            )
        );
        $field = 'birthday';
        $this->add(
            array(
                'name' => $field,
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
                    'id' => $field,
                    'required' => 'required',
                    'value' => $orderedSettings[$field]['value'],
                    // 'value' => 1
                ),
                'options' => array(
                    'label' => $orderedSettings[$field]['label'],
                    'empty_option' => 'Please choose your birthday',
                ),
            )
        );

        $field = 'about_me';
        $this->add(
            array(
                'name' => $field,
                'type' => 'Zend\Form\Element\Textarea',
                'attributes' => array(
                    'id' => $field,
                    'value' => $orderedSettings[$field]['value'],
                    //'placeholder' => 'Setting key',
                    //'required' => 'required',
                ),
                'options' => array(
                    'label' => $orderedSettings[$field]['label'],
                ),
            )
        );

        $field = 'receive_newsletter';
        $this->add(
            array(
                'name' => $field,
                'type' => 'Zend\Form\Element\Checkbox',
                'attributes' => array(
                    'id' => $field,
                    'value' => $orderedSettings[$field]['value'],
                    // 'required' => 'required',

                ),
                'options' => array(
                    'label' => $orderedSettings[$field]['label'],
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                ),
            )
        );

        $field = 'submit';
        $this->add(
            array(
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
            )
        );

        // p($orderedSettings);
        // exit;
    }
}