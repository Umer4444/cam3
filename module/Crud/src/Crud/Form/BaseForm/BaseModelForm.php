<?php
/**
 * This file is generated automatically for table "model". Do not change its
 * contents as it will be overwritten in next pass of generator run.
 *
 * @author VisioCrudModeler
 * @project CamClients
 * @license MIT
 * @copyright CamClients
 */


namespace Crud\Form\BaseForm;

class BaseModelForm extends \VisioCrudModeler\Form\AbstractForm
{

    public function __construct()
    {
        parent::__construct('model');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Id',
            ),
        ));

        $this->add(array(
            'name' => 'likes',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Likes',
            ),
        ));

        $this->add(array(
            'name' => 'dislikes',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Dislikes',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));

        $this->add(array(
            'name' => 'first_name',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'FirstName',
            ),
        ));

        $this->add(array(
            'name' => 'screen_name',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'ScreenName',
            ),
        ));

        $this->add(array(
            'name' => 'new_screen_name',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'NewScreenName',
            ),
        ));

        $this->add(array(
            'name' => 'id_cover',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'IdCover',
            ),
        ));

        $this->add(array(
            'name' => 'gender',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Gender',
            ),
        ));

        $this->add(array(
            'name' => 'about_me',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'AboutMe',
            ),
        ));

        $this->add(array(
            'name' => 'country',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Country',
            ),
        ));

        $this->add(array(
            'name' => 'region',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Region',
            ),
        ));

        $this->add(array(
            'name' => 'city',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'City',
            ),
        ));

        $this->add(array(
            'name' => 'region_id',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'RegionId',
            ),
        ));

        $this->add(array(
            'name' => 'zip_code',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'ZipCode',
            ),
        ));

        $this->add(array(
            'name' => 'address',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Address',
            ),
        ));

        $this->add(array(
            'name' => 'address_real',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'AddressReal',
            ),
        ));

        $this->add(array(
            'name' => 'phone',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Phone',
            ),
        ));

        $this->add(array(
            'name' => 'payment_method',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'PaymentMethod',
            ),
        ));

        $this->add(array(
            'name' => 'payment_currency',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'PaymentCurrency',
            ),
        ));

        $this->add(array(
            'name' => 'payment_min_amount',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'PaymentMinAmount',
            ),
        ));

        $this->add(array(
            'name' => 'body_type',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'BodyType',
            ),
        ));

        $this->add(array(
            'name' => 'ethnicity',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Ethnicity',
            ),
        ));

        $this->add(array(
            'name' => 'height',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Height',
            ),
        ));

        $this->add(array(
            'name' => 'weight',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Weight',
            ),
        ));

        $this->add(array(
            'name' => 'measurements_bust',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'MeasurementsBust',
            ),
        ));

        $this->add(array(
            'name' => 'measurements_waist',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'MeasurementsWaist',
            ),
        ));

        $this->add(array(
            'name' => 'measurements_bottom',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'MeasurementsBottom',
            ),
        ));

        $this->add(array(
            'name' => 'hair_color',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'HairColor',
            ),
        ));

        $this->add(array(
            'name' => 'orientation',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Orientation',
            ),
        ));

        $this->add(array(
            'name' => 'birthday',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Birthday',
            ),
        ));

        $this->add(array(
            'name' => 'birthday_real',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'BirthdayReal',
            ),
        ));

        $this->add(array(
            'name' => 'ssn',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Ssn',
            ),
        ));

        $this->add(array(
            'name' => 'status',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Status',
            ),
        ));

        $this->add(array(
            'name' => 'status_profile',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'StatusProfile',
            ),
        ));

        $this->add(array(
            'name' => 'rating',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Rating',
            ),
        ));

        $this->add(array(
            'name' => 'votes',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Votes',
            ),
        ));

        $this->add(array(
            'name' => 'next_performance',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'NextPerformance',
            ),
        ));

        $this->add(array(
            'name' => 'joined',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Joined',
            ),
        ));

        $this->add(array(
            'name' => 'last_login',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'LastLogin',
            ),
        ));

        $this->add(array(
            'name' => 'chips',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Chips',
            ),
        ));

        $this->add(array(
            'name' => 'chat_type',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'ChatType',
            ),
        ));

        $this->add(array(
            'name' => 'active',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Active',
            ),
        ));

        $this->add(array(
            'name' => 'reset_code',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'ResetCode',
            ),
        ));

        $this->add(array(
            'name' => 'timezone',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Timezone',
            ),
        ));

        $this->add(array(
            'name' => 'rep_share',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'RepShare',
            ),
        ));

        $this->add(array(
            'name' => 'last_activity',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'LastActivity',
            ),
        ));

        $this->add(array(
            'name' => 'online',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Online',
            ),
        ));

        $this->add(array(
            'name' => 'session_id',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'SessionId',
            ),
        ));

        $this->add(array(
            'name' => 'last_notification',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'LastNotification',
            ),
        ));

        $this->add(array(
            'name' => 'notification_email',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'NotificationEmail',
            ),
        ));

        $this->add(array(
            'name' => 'activation_code',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'ActivationCode',
            ),
        ));

        $this->add(array(
            'name' => 'terms_agreed',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'TermsAgreed',
            ),
        ));

        $this->add(array(
            'name' => 'terms_signature',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'TermsSignature',
            ),
        ));

        $this->add(array(
            'name' => 'display_order',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'DisplayOrder',
            ),
        ));

        $this->add(array(
            'name' => 'gift_city',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'GiftCity',
            ),
        ));

        $this->add(array(
            'name' => 'gift_country',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'GiftCountry',
            ),
        ));

        $this->add(array(
            'name' => 'gift_region',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'GiftRegion',
            ),
        ));

        $this->add(array(
            'name' => 'gift_zip',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'GiftZip',
            ),
        ));

        $this->add(array(
            'name' => 'gift_address',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'GiftAddress',
            ),
        ));

        $this->add(array(
            'name' => 'same_address',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'SameAddress',
            ),
        ));

        $this->add(array(
            'name' => 'gift_office_address',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'GiftOfficeAddress',
            ),
        ));

        $this->add(array(
            'name' => 'chat_timeout',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'ChatTimeout',
            ),
        ));

        $this->add(array(
            'name' => 'auto_approve',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'AutoApprove',
            ),
        ));

        $this->add(array(
            'name' => 'guestbook',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Guestbook',
            ),
        ));

        $this->add(array(
            'name' => 'ip_address',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'IpAddress',
            ),
        ));

        $this->add(array(
            'name' => 'model_id',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'ModelId',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'form-control btn-success',
                'style' => 'width: 50%'
            ),
        ), ['priority' => -1000]);
    }


}

