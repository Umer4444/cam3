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


namespace Crud\Grid\BaseGrid;

class BaseModelGrid extends \ZfTable\AbstractTable
{

    protected $config = array(
        'name' => '',
        'showPagination' => true,
        'showQuickSearch' => false,
        'showItemPerPage' => true,
        'itemCountPerPage' => 10,
        'showColumnFilters' => false,
    );

    protected $headers = array(
        'id' => array(
            'title' => 'Id',
            'width' => '100',
            'filters' => 'text',
        ),
        'likes' => array(
            'title' => 'Likes',
            'width' => '100',
            'filters' => 'text',
        ),
        'dislikes' => array(
            'title' => 'Dislikes',
            'width' => '100',
            'filters' => 'text',
        ),
        'email' => array(
            'title' => 'Email',
            'width' => '100',
            'filters' => 'text',
        ),
        'password' => array(
            'title' => 'Password',
            'width' => '100',
            'filters' => 'text',
        ),
        'name' => array(
            'title' => 'Name',
            'width' => '100',
            'filters' => 'text',
        ),
        'first_name' => array(
            'title' => 'First name',
            'width' => '100',
            'filters' => 'text',
        ),
        'screen_name' => array(
            'title' => 'Screen name',
            'width' => '100',
            'filters' => 'text',
        ),
        'new_screen_name' => array(
            'title' => 'New screen name',
            'width' => '100',
            'filters' => 'text',
        ),
        'id_cover' => array(
            'title' => 'Id cover',
            'width' => '100',
            'filters' => 'text',
        ),
        'gender' => array(
            'title' => 'Gender',
            'width' => '100',
            'filters' => 'text',
        ),
        'about_me' => array(
            'title' => 'About me',
            'width' => '100',
            'filters' => 'text',
        ),
        'country' => array(
            'title' => 'Country',
            'width' => '100',
            'filters' => 'text',
        ),
        'region' => array(
            'title' => 'Region',
            'width' => '100',
            'filters' => 'text',
        ),
        'city' => array(
            'title' => 'City',
            'width' => '100',
            'filters' => 'text',
        ),
        'region_id' => array(
            'title' => 'Region id',
            'width' => '100',
            'filters' => 'text',
        ),
        'zip_code' => array(
            'title' => 'Zip code',
            'width' => '100',
            'filters' => 'text',
        ),
        'address' => array(
            'title' => 'Address',
            'width' => '100',
            'filters' => 'text',
        ),
        'address_real' => array(
            'title' => 'Address real',
            'width' => '100',
            'filters' => 'text',
        ),
        'phone' => array(
            'title' => 'Phone',
            'width' => '100',
            'filters' => 'text',
        ),
        'payment_method' => array(
            'title' => 'Payment method',
            'width' => '100',
            'filters' => 'text',
        ),
        'payment_currency' => array(
            'title' => 'Payment currency',
            'width' => '100',
            'filters' => 'text',
        ),
        'payment_min_amount' => array(
            'title' => 'Payment min amount',
            'width' => '100',
            'filters' => 'text',
        ),
        'body_type' => array(
            'title' => 'Body type',
            'width' => '100',
            'filters' => 'text',
        ),
        'ethnicity' => array(
            'title' => 'Ethnicity',
            'width' => '100',
            'filters' => 'text',
        ),
        'height' => array(
            'title' => 'Height',
            'width' => '100',
            'filters' => 'text',
        ),
        'weight' => array(
            'title' => 'Weight',
            'width' => '100',
            'filters' => 'text',
        ),
        'measurements_bust' => array(
            'title' => 'Measurements bust',
            'width' => '100',
            'filters' => 'text',
        ),
        'measurements_waist' => array(
            'title' => 'Measurements waist',
            'width' => '100',
            'filters' => 'text',
        ),
        'measurements_bottom' => array(
            'title' => 'Measurements bottom',
            'width' => '100',
            'filters' => 'text',
        ),
        'hair_color' => array(
            'title' => 'Hair color',
            'width' => '100',
            'filters' => 'text',
        ),
        'orientation' => array(
            'title' => 'Orientation',
            'width' => '100',
            'filters' => 'text',
        ),
        'birthday' => array(
            'title' => 'Birthday',
            'width' => '100',
            'filters' => 'text',
        ),
        'birthday_real' => array(
            'title' => 'Birthday real',
            'width' => '100',
            'filters' => 'text',
        ),
        'ssn' => array(
            'title' => 'Ssn',
            'width' => '100',
            'filters' => 'text',
        ),
        'status' => array(
            'title' => 'Status',
            'width' => '100',
            'filters' => 'text',
        ),
        'status_profile' => array(
            'title' => 'Status profile',
            'width' => '100',
            'filters' => 'text',
        ),
        'rating' => array(
            'title' => 'Rating',
            'width' => '100',
            'filters' => 'text',
        ),
        'votes' => array(
            'title' => 'Votes',
            'width' => '100',
            'filters' => 'text',
        ),
        'next_performance' => array(
            'title' => 'Next performance',
            'width' => '100',
            'filters' => 'text',
        ),
        'joined' => array(
            'title' => 'Joined',
            'width' => '100',
            'filters' => 'text',
        ),
        'last_login' => array(
            'title' => 'Last login',
            'width' => '100',
            'filters' => 'text',
        ),
        'chips' => array(
            'title' => 'Chips',
            'width' => '100',
            'filters' => 'text',
        ),
        'chat_type' => array(
            'title' => 'Chat type',
            'width' => '100',
            'filters' => 'text',
        ),
        'active' => array(
            'title' => 'Active',
            'width' => '100',
            'filters' => 'text',
        ),
        'reset_code' => array(
            'title' => 'Reset code',
            'width' => '100',
            'filters' => 'text',
        ),
        'timezone' => array(
            'title' => 'Timezone',
            'width' => '100',
            'filters' => 'text',
        ),
        'rep_share' => array(
            'title' => 'Rep share',
            'width' => '100',
            'filters' => 'text',
        ),
        'last_activity' => array(
            'title' => 'Last activity',
            'width' => '100',
            'filters' => 'text',
        ),
        'online' => array(
            'title' => 'Online',
            'width' => '100',
            'filters' => 'text',
        ),
        'session_id' => array(
            'title' => 'Session id',
            'width' => '100',
            'filters' => 'text',
        ),
        'last_notification' => array(
            'title' => 'Last notification',
            'width' => '100',
            'filters' => 'text',
        ),
        'notification_email' => array(
            'title' => 'Notification email',
            'width' => '100',
            'filters' => 'text',
        ),
        'activation_code' => array(
            'title' => 'Activation code',
            'width' => '100',
            'filters' => 'text',
        ),
        'terms_agreed' => array(
            'title' => 'Terms agreed',
            'width' => '100',
            'filters' => 'text',
        ),
        'terms_signature' => array(
            'title' => 'Terms signature',
            'width' => '100',
            'filters' => 'text',
        ),
        'display_order' => array(
            'title' => 'Display order',
            'width' => '100',
            'filters' => 'text',
        ),
        'gift_city' => array(
            'title' => 'Gift city',
            'width' => '100',
            'filters' => 'text',
        ),
        'gift_country' => array(
            'title' => 'Gift country',
            'width' => '100',
            'filters' => 'text',
        ),
        'gift_region' => array(
            'title' => 'Gift region',
            'width' => '100',
            'filters' => 'text',
        ),
        'gift_zip' => array(
            'title' => 'Gift zip',
            'width' => '100',
            'filters' => 'text',
        ),
        'gift_address' => array(
            'title' => 'Gift address',
            'width' => '100',
            'filters' => 'text',
        ),
        'same_address' => array(
            'title' => 'Same address',
            'width' => '100',
            'filters' => 'text',
        ),
        'gift_office_address' => array(
            'title' => 'Gift office address',
            'width' => '100',
            'filters' => 'text',
        ),
        'chat_timeout' => array(
            'title' => 'Chat timeout',
            'width' => '100',
            'filters' => 'text',
        ),
        'auto_approve' => array(
            'title' => 'Auto approve',
            'width' => '100',
            'filters' => 'text',
        ),
        'guestbook' => array(
            'title' => 'Guestbook',
            'width' => '100',
            'filters' => 'text',
        ),
        'ip_address' => array(
            'title' => 'Ip address',
            'width' => '100',
            'filters' => 'text',
        ),
        'model_id' => array(
            'title' => 'Model id',
            'width' => '100',
            'filters' => 'text',
        ),
        'edit' => array(
            'title' => 'Edit',
            'width' => '100',
        ),
        'delete' => array(
            'title' => 'Delete',
            'width' => '100',
        ),
    );

    public function init()
    {
        foreach (get_class_methods($this) as $method) { if (substr($method, 0, 2) == "on") {$this->$method();}} 
        $this->getHeader("edit")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/model/update/%s\">Edit</a>", $record->getId());//model_id
            }
        ));

        $this->getHeader("delete")->getCell()->addDecorator("callable", array(
            "callable" => function($context, $record){
                return sprintf("<a href=\"/admin/crud/model/delete/%s\">Delete</a>", $record->getId());
            }
        ));
    }

    protected function initFilters($query)
    {
        $value = $this->getParamAdapter()->getValueOfFilter('id');
        if ($value != null) {
            $query->where("id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('likes');
        if ($value != null) {
            $query->where("likes like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('dislikes');
        if ($value != null) {
            $query->where("dislikes like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('email');
        if ($value != null) {
            $query->where("email like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('password');
        if ($value != null) {
            $query->where("password like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('name');
        if ($value != null) {
            $query->where("name like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('first_name');
        if ($value != null) {
            $query->where("first_name like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('screen_name');
        if ($value != null) {
            $query->where("screen_name like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('new_screen_name');
        if ($value != null) {
            $query->where("new_screen_name like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('id_cover');
        if ($value != null) {
            $query->where("id_cover like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('gender');
        if ($value != null) {
            $query->where("gender like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('about_me');
        if ($value != null) {
            $query->where("about_me like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('country');
        if ($value != null) {
            $query->where("country like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('region');
        if ($value != null) {
            $query->where("region like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('city');
        if ($value != null) {
            $query->where("city like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('region_id');
        if ($value != null) {
            $query->where("region_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('zip_code');
        if ($value != null) {
            $query->where("zip_code like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('address');
        if ($value != null) {
            $query->where("address like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('address_real');
        if ($value != null) {
            $query->where("address_real like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('phone');
        if ($value != null) {
            $query->where("phone like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('payment_method');
        if ($value != null) {
            $query->where("payment_method like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('payment_currency');
        if ($value != null) {
            $query->where("payment_currency like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('payment_min_amount');
        if ($value != null) {
            $query->where("payment_min_amount like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('body_type');
        if ($value != null) {
            $query->where("body_type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('ethnicity');
        if ($value != null) {
            $query->where("ethnicity like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('height');
        if ($value != null) {
            $query->where("height like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('weight');
        if ($value != null) {
            $query->where("weight like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('measurements_bust');
        if ($value != null) {
            $query->where("measurements_bust like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('measurements_waist');
        if ($value != null) {
            $query->where("measurements_waist like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('measurements_bottom');
        if ($value != null) {
            $query->where("measurements_bottom like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('hair_color');
        if ($value != null) {
            $query->where("hair_color like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('orientation');
        if ($value != null) {
            $query->where("orientation like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('birthday');
        if ($value != null) {
            $query->where("birthday like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('birthday_real');
        if ($value != null) {
            $query->where("birthday_real like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('ssn');
        if ($value != null) {
            $query->where("ssn like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('status');
        if ($value != null) {
            $query->where("status like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('status_profile');
        if ($value != null) {
            $query->where("status_profile like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('rating');
        if ($value != null) {
            $query->where("rating like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('votes');
        if ($value != null) {
            $query->where("votes like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('next_performance');
        if ($value != null) {
            $query->where("next_performance like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('joined');
        if ($value != null) {
            $query->where("joined like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('last_login');
        if ($value != null) {
            $query->where("last_login like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('chips');
        if ($value != null) {
            $query->where("chips like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('chat_type');
        if ($value != null) {
            $query->where("chat_type like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('active');
        if ($value != null) {
            $query->where("active like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('reset_code');
        if ($value != null) {
            $query->where("reset_code like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('timezone');
        if ($value != null) {
            $query->where("timezone like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('rep_share');
        if ($value != null) {
            $query->where("rep_share like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('last_activity');
        if ($value != null) {
            $query->where("last_activity like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('online');
        if ($value != null) {
            $query->where("online like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('session_id');
        if ($value != null) {
            $query->where("session_id like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('last_notification');
        if ($value != null) {
            $query->where("last_notification like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('notification_email');
        if ($value != null) {
            $query->where("notification_email like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('activation_code');
        if ($value != null) {
            $query->where("activation_code like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('terms_agreed');
        if ($value != null) {
            $query->where("terms_agreed like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('terms_signature');
        if ($value != null) {
            $query->where("terms_signature like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('display_order');
        if ($value != null) {
            $query->where("display_order like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('gift_city');
        if ($value != null) {
            $query->where("gift_city like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('gift_country');
        if ($value != null) {
            $query->where("gift_country like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('gift_region');
        if ($value != null) {
            $query->where("gift_region like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('gift_zip');
        if ($value != null) {
            $query->where("gift_zip like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('gift_address');
        if ($value != null) {
            $query->where("gift_address like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('same_address');
        if ($value != null) {
            $query->where("same_address like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('gift_office_address');
        if ($value != null) {
            $query->where("gift_office_address like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('chat_timeout');
        if ($value != null) {
            $query->where("chat_timeout like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('auto_approve');
        if ($value != null) {
            $query->where("auto_approve like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('guestbook');
        if ($value != null) {
            $query->where("guestbook like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('ip_address');
        if ($value != null) {
            $query->where("ip_address like '%".$value."%' ");
        }

        $value = $this->getParamAdapter()->getValueOfFilter('model_id');
        if ($value != null) {
            $query->where("model_id like '%".$value."%' ");
        }
    }


}

