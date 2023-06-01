<?php

use Application\Entity\UserCategory;
use PerfectWeb\Core\Utils\Status;

class ModelController extends App_Controller_Action
{

    protected $em = null;

    /**
     * @throws Zend_Exception
     */
    public function init()
    {

        $this->em = Zend_Registry::get('service_manager')->get('Doctrine\ORM\EntityManager');

        parent::init();

        $action = $this->_request->action;

        $this->load("user_notifications");
        //$this->_data["notification_count"] = $this->user_notifications->getUnreadCount("model", $_SESSION["user"]["id"]);
        $this->_data["notification_count"] = $this->user_notifications->getNewNotificationCount("model",
            $_SESSION["user"]["id"],
            (isset($_SESSION["user"]["last_notification"]) ? $_SESSION["user"]["last_notification"] : 0)
            //$this->acl->isAllowed($_SESSION['group'], "all_resources", "view")
        );
        unset($this->user_notifications);

        //load development pages menu
        $this->load('static_pages');

        $parentPagesTop = $this->static_pages->getPages('backend');
        $this->_data['pages'] = array();

        foreach ($parentPagesTop as $pageTop) {
            $childrenTop = array();
            $parentPagesLvl1 = $this->static_pages->getPages('backend', $pageTop->page);
            foreach ($parentPagesLvl1 as $pageLvl1) {
                $childrenTop[] = array("page" => $pageLvl1->page, "title" => $pageLvl1->title);
            }
            $this->_data['pages'][] = array("page" => $pageTop->page, "title" => $pageTop->title, 'children' => $childrenTop);
        }

        if (Auth::isModel()) {
            $this->load("messages");
            $this->_data['unread_count'] = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group']);
            unset($this->messages);
        }

    }

    /**
     *
     */
    public function forgotAction()
    {
    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Mail_Exception
     */
    public function pwresetAction()
    {

        if ($_SESSION['user']['id']) $this->_redirect('/performer/');
        $this->load('model');
        $this->_data['var'] = $this->request->var;

        if ($this->request->var && $this->request->var != 'done') {
            $this->_data['user'] = $this->model->fetchRow($this->model->select()->where("reset_code=?", $this->request->var));
        }

        if ($this->request->isPost()) {
            $post = $this->_request->getPost();

            if ($post['submit'] == 'Submit' && $post['email']) {

                $get_user = $this->model->fetchRow($this->model->select()->where("email=?", $post['email']));

                if (!$get_user->email) {
                    $this->_helper->FlashMessenger->addMessage(notice("We could not find an account that matches that email", false));
                    $this->_redirect('/model/pwreset/');
                }

                if ($get_user->active == 0) {
                    $this->_helper->FlashMessenger->addMessage(notice("You need to activate your account first.", false));
                    $this->_redirect('/model/pwreset/');
                }

                $reset_code = md5(md5(microtime() . $get_user->email . 'n8&^W') . '-' . substr(md5('p8*&g+' . rand(11, 99999) . 'cu7^S'), 2, 14));

                $link = "http://" . strtolower(config()->site_name) . "/model/pwreset/" . $reset_code;

                $this->load("templates");
                $tmpl = $this->templates->getContent("password_recovery");

                $message = $tmpl->content;
                $message = str_replace("{name}", $get_user->screen_name, $message);
                $message = str_replace("{url}", $link, $message);
                $message = str_replace("{link}", '<a href="' . $link . '">click here</a>', $message);

                $mail = new Zend_Mail();
                $mail->setFrom('no-reply@' . strtolower(config()->site_name), config()->site_name);
                $mail->addTo($get_user->email);
                $mail->setSubject($tmpl->title);
                $mail->setBodyHtml($message);

                if ($mail->send()) {
                    $this->model->update(array("reset_code" => $reset_code), "id=" . $get_user->id);
                    $this->_helper->FlashMessenger->addMessage(notice("An email has been sent to <i>" . $get_user->email . "</i> that will allow you to reset your password"));
                    $this->_redirect('/performer/pwreset/done');
                } else {
                    $this->_helper->FlashMessenger->addMessage(notice("There was a problem, please try again", false));
                }

                if (isset($notice_msq)) {

                    $this->_helper->FlashMessenger->addMessage(notice($notice_msq));
                };
            }

            if ($post['reset'] == 'Reset' && $post['password'] != '' && $post['password'] == $post['confirm_password']) {

                if (!$this->_data['user']->id) {
                    $this->_helper->FlashMessenger->addMessage(notice("Invalid verification code", false));
                    $this->_redirect('/performer/pwreset/' . $this->request->var);
                }

                $this->model->update(array("password" => md5($post['password']), "reset_code" => ""), "id=" . $this->_data['user']->id);

                $this->_helper->FlashMessenger->addMessage(notice("Your password successfully changed. You can login now.", true));
                $this->redirectToLogin('model');
            }

            $this->_redirect('/performer/pwreset/');
        }

    }

    /**
     * @throws Zend_Exception
     */
    public function accountsettingsAction()
    {

        $this->load('static_pages');
        $this->load('model');
        $this->load('countries');

        $this->_data['terms'] = $this->static_pages->getContent('model_release_form');

        $this->_data['model'] = $this->model->getModel((int)$_SESSION['user']['user_id']);

        $this->load('timezones');
        $this->_data['timezones'] = $this->timezones->fetchAll();

        if ($this->_data['model']->country > 0) {
            $this->_data['country'] = $this->countries->getLocationById($this->_data['model']->country);
        } else {
            $this->_data['country'] = '';
        }

        if ($this->_data['model']->region > 0) {
            $this->_data['region'] = $this->countries->getLocationById($this->_data['model']->region);
        } else {
            $this->_data['region'] = '';
        }

        if ($this->_data['model']->city > 0) {
            $this->_data['city'] = $this->countries->getLocationById($this->_data['model']->city);
        } else {
            $this->_data['city'] = '';
        }

        /*if ($this->_data['model']->gift_country > 0) {
            $this->_data['gift_country'] = $this->countries->getLocationById($this->_data['model']->gift_country);
        } else {
            $this->_data['gift_country'] = '';
        }

        if ($this->_data['model']->gift_region > 0) {
            $this->_data['gift_region'] = $this->countries->getLocationById($this->_data['model']->gift_region);
        } else {
            $this->_data['gift_region'] = '';
        }

        if ($this->_data['model']->gift_city > 0) {
            $this->_data['gift_city'] = $this->countries->getLocationById($this->_data['model']->gift_city);
        } else {
            $this->_data['gift_city'] = '';
        }*/

        if ($this->request->isPost()) {
            $post = $this->_request->getPost();

            if ($this->_request->save == 'Save') {
                $notice_msq = "Your account settings have been successfully saved!";

                //add to zf2
                $this->hydratePostToEntity($post);

                if (!$post['password'] || $post['password'] != $post['confirm_password']) {
                    unset($post['password']);
                    if ($post['password'] != $post['confirm_password']) {
                        $notice_msq = "Your confirmation password didn't match! Try again.";
                    }

                } else {
                    if (Auth::isModel()) {
                        if (isset($post['old_password']) && md5($post['old_password']) == $this->_data['model']->password) {
                            $post['password'] = md5($post['password']);
                        } else {
                            unset($post['password']);
                            unset($post['old_password']);
                            unset($post['confirm_password']);
                            $notice_msq = "Your old password didn't match! Try again.";
                        }
                    } else {
                        $post['password'] = md5($post['password']);
                    }
                }

                //file uploads
                $this->load('upload');
                $file_upload_failed = '';
                if (isset($_FILES['headshot']) && is_uploaded_file($_FILES['headshot']['tmp_name'])) {
                    $photo_dir = APPLICATION_PATH . '/../../public/uploads/users/'.$_SESSION["user"]["id"].'/profile/';

                    $upload = $this->upload->uploadPhoto($photo_dir, 'headshot');

                    if ($upload['status'] == 'success') {
                        $filename = $upload['file'];
                        $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 480, 640);

                        $current_cover = $this->_data['model']->getHeadshot(1);

                        if ($current_cover->id) {
                            //delete old cover file and remove from db
                            db()->query("delete from photos where " . db()->quoteInto("id=?", $current_cover->id));
                            unlink(APPLICATION_PATH . '/../../public/uploads/users/'.$_SESSION["user"]["id"].'/profile/' . $current_cover->filename);
                        }

                        //save new  cover in db tbl photos,
                        db()->query("insert into photos set " . db()->quoteInto("user=?", $_SESSION['user']['id']) . ", " . db()->quoteInto("filename=?", '/uploads/users/'.$_SESSION["user"]["id"].'/profile/'.$filename) .",status=1,type='headshot' ");

                    } else {
                        $file_upload_failed .= "<br>" . $upload['message'];
                    }
                } else {
                    //delete headshot
                    if ($post['delete_headshot']) {
                        $current_cover = $this->_data['model']->getHeadshot(1);
                        if ($current_cover->id) {
                            db()->query("delete from photos where " . db()->quoteInto("id=?", $current_cover->id));
                            unlink(APPLICATION_PATH . '/../../public/uploads/users/'.$_SESSION["user"]["id"]
                                   .'/profile/' . $current_cover->filename);
                        }
                    }
                }

                if (isset($_FILES['photo_id']) && is_uploaded_file($_FILES['photo_id']['tmp_name'])) {
                    $photo_dir = APPLICATION_PATH . '/../../public/uploads/users/'.$_SESSION["user"]["id"].'/profile/';
                    $upload = $this->upload->uploadPhoto($photo_dir, 'photo_id');

                    if ($upload['status'] == 'success') {
                        $filename = $upload['file'];
                        $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 640, 480);

                        $current_cover = $this->_data['model']->getPhotoId(1);


                        db()->query("insert into photos set " . db()->quoteInto("user=?", $_SESSION['user']['id']) . ", " . db()->quoteInto("filename=?", '/uploads/users/'.$_SESSION["user"]["id"].'/profile/'.$filename) .",status=".Status::PENDING.",type='photo_id' ");


                    } else {
                        $file_upload_failed .= "<br>" . $upload['message'];
                    }
                }

                if (isset($_FILES['2257_form']) && is_uploaded_file($_FILES['2257_form']['tmp_name'])) {
                    $photo_dir = APPLICATION_PATH . '/../../public/uploads/users/'.$_SESSION["user"]["id"].'/profile/';

                    $upload = $this->upload->uploadPhoto($photo_dir, '2257_form');

                    if ($upload['status'] == 'success') {
                        $filename = $upload['file'];
                        $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 600, 800);

                        //save new  cover in db tbl photos,
                        db()->query("insert into photos set " . db()->quoteInto("user=?", $_SESSION['user']['id']) .
                                    ", " . db()->quoteInto("filename=?", '/uploads/users/'.$_SESSION["user"]["id"]
                                                                                                           .'/profile/'.$filename) .
                                    ",status=".Status::PENDING.",type='2257_form' ");

                    } else {
                        $file_upload_failed .= "<br>" . $upload['message'];
                    }
                }

                if (isset($_FILES['w9_form']) && is_uploaded_file($_FILES['w9_form']['tmp_name'])) {
                    $photo_dir = APPLICATION_PATH . '/../../public/uploads/users/'.$_SESSION["user"]["id"].'/profile/';

                    $upload = $this->upload->uploadPhoto($photo_dir, 'w9_form');

                    if ($upload['status'] == 'success') {
                        $filename = $upload['file'];
                        $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 600, 800);

                        //save new  cover in db tbl photos,
                        db()->query("insert into photos set " . db()->quoteInto("user=?", $_SESSION['user']['id']) .
                                    ", " . db()->quoteInto("filename=?", '/uploads/users/'.$_SESSION["user"]["id"]
                                                                                                           .'/profile/'.$filename) .
",status='".Status::PENDING."',type='w9_form' ");


                    } else {
                        $file_upload_failed .= "<br>" . $upload['message'];
                    }
                }
                /* && $this->_data["model"]->terms_agreed == false*/
                $statarray1 = array(null, "0", "2");

                if (isset($post['terms_agreed']) && in_array($this->_data["model"]->terms_agreed, $statarray1)) {

                    $post['terms_agreed'] = 1;
                    $notice_msq = "Performer " . $this->_data["model"]->screen_name . " has agreed with Form Release ";
                    //$notificationType = "form_release_agreement";
                    $notificationType = "model-release-agreement";

                }
                else if (!isset($post['terms_agreed']) && in_array($this->_data["model"]->terms_agreed, array("1", "2"))) {

                    $post['terms_agreed'] = 0;
                    $notificationType = "model-release-disagreement";
                    $notice_msq = "Performer " . $this->_data["model"]->screen_name . " has disagreed with Form Release ";

                } else {
                    unset($post['terms_agreed']);
                    $notificationType = "model-account-manage";
                }

                if (isset($_FILES['release_form']) && is_uploaded_file($_FILES['release_form']['tmp_name'])) {
                    $photo_dir = APPLICATION_PATH . '/../../public/uploads/users/'.$_SESSION["user"]["id"].'/profile/';

                    $upload = $this->upload->uploadPhoto($photo_dir, 'release_form');

                    if ($upload['status'] == 'success') {
                        $filename = $upload['file'];
                        $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 600, 800);

                        //save new  cover in db tbl photos,
                        db()->query("insert into photos set " . db()->quoteInto("user=?", $_SESSION['user']['id']) .
                                    ", " . db()->quoteInto("filename=?", '/uploads/users/'.$_SESSION["user"]["id"]
                                                                                                           .'/profile/'.$filename) .
",status=".Status::PENDING.",type='release_form' ");

                    } else {
                        $file_upload_failed .= "<br>" . $upload['message'];
                    }
                }


                $post['birthday_real'] = $post['birthday_year'] . '-' . $post['birthday_month'] . '-' . $post['birthday_day'];
                unset($post['birthday_year']);
                unset($post['birthday_month']);
                unset($post['birthday_day']);

                unset($post['save']);
                unset($post['old_password']);
                unset($post['confirm_password']);
                unset($post['delete_headshot']);
                unset($post['delete_photo_id']);
                unset($post['headshot']);
                unset($post['photo_id']);
                unset($post['2257_form']);
                unset($post['w9_form']);
                unset($post['release_form']);

                unset($post['country_name']);
                unset($post['country_code']);
                unset($post['region_name']);
                unset($post['region_code']);
                unset($post['city_name']);
                unset($post['city_code']);

                unset($post['gift_country_name']);
                unset($post['gift_country_code']);

                unset($post['gift_region_name']);
                unset($post['gift_region_code']);
                unset($post['gift_city_name']);
                unset($post['gift_city_code']);

                unset($post['country']);

                /* bad words filters */
                $this->load("bad_words");
                $badWords = $this->bad_words->getAllArray();
                array_walk($post, 'badWords', $badWords);
                /* end bad words filter */

                if ($this->model->update($post, $this->model->getAdapter()->quoteInto("id=?", $_SESSION['user']['id'])))
                    $notice_msq = "Performer " . $this->_data["model"]->screen_name . " changed account settings";
                else
                    $notice_msq = "";

                if (!empty($notice_msq) && ($notice_msq != "Your confirmation password didn't match! Try again." || $notice_msq != "Your old password didn't match! Try again.")) {
                    $this->load("notifications");
                }

                $this->_helper->FlashMessenger->addMessage(notice($notice_msq . $file_upload_failed));
            }

            $this->_redirect($this->view->url(array(), 'model-account-settings'));
        }

    }

    /**
     * @throws Zend_Exception
     */
    public function paymentInfoAction()
    {
        $this->load('model');
        $this->load('countries');
        $this->load('payment_method');
        $this->load('payments_info');

        //$this->_data['countries'] = $this->countries->fetchAllLocations('co');
        $userRepo = $this->em->getRepository(\Application\Entity\User::class);
        $user = $userRepo->findOneBy(array("id" => (int)$_SESSION['user']['user_id']));
        $this->_data['payment_methods'] = $this->payment_method->getPaymentMethods();
        $this->_data['payments_info'] = $this->payments_info->getPaymentsInfoByUserId($user->getId(), 'model');

        if ($this->request->isPost()) {
            $post = $this->_request->getPost();

            if ($this->_request->save == 'Save' && $this->_request->payment_method) {

                $this->model->update(array("payment_method" => $post['payment_method'], "payment_currency" => $post['payment_currency'], "payment_min_amount" => $post['payment_min_amount']), $this->model->getAdapter()->quoteInto("id=?", $_SESSION['user']['id']));

                if ($post['paypal_account']) {
                    $this->payments_info->updatePaymentInfo($this->_data['model']->id, 'model', 1, array("paypal_account" => $post['paypal_account']));
                }
                if ($post['paxum_account']) {
                    $this->payments_info->updatePaymentInfo($this->_data['model']->id, 'model', 2, array("paxum_account" => $post['paxum_account']));
                }
                if ($post['CCBill_account']) {
                    $this->payments_info->updatePaymentInfo($this->_data['model']->id, 'model', 6, array("CCBill_account" => $post['CCBill_account']));
                }

                $this->_helper->FlashMessenger->addMessage(notice("Your payment info have been successfully saved!"));
            }

            $this->_redirect($this->view->url(array(),'model-payment-info'));
        }
    }

    /**
     * @param array $post
     */
    private function hydratePostToEntity(array $post)
    {

        if (!is_array($post)) return false;


        //update user zf2 tables entity

        //get user entity zf2
        $userRepo = $this->em->getRepository(\Application\Entity\User::class);
        $userEntity = $userRepo->findOneBy(array("id" => (int)$_SESSION['user']['id']));

        //preparing resources
        $resourcesProfile = $userRepo->getProfileSettings($userEntity);
        $resourcesProfileSettings = $userRepo->getPerformerProfile($userEntity);
        $resourcesAccount = $userRepo->getAccountSettings($userEntity);

        //$resourcesSettings = $resourceRepo->findBy(array('group' => 'settings'));
        $resources = array_merge($resourcesProfile, $resourcesAccount, /* $resourcesSettings,*/
            $resourcesProfileSettings);


        //@todo: change cover
        // $userEntity->setBirthday($post['birthday_year'] . '-' . $post['birthday_month'] . '-' . $post['birthday_day']);

        //@todo: approve displayname changes
        // $userEntity->setDisplayName($post['screen_name']);

        //@todo model categories

        //@todo model documents to zf2

        // array map resource key => post key
        $keyMap = array(
            "phone" => "phone",
            "birthday" => "birthday",
            "about_me" => "about_me",
            "gender" => "gender",
            //"body_type" => "body_type",
            "ethnicity" => "ethnicity",
            "hair_color" => "hair_color",
            "orientation" => "orientation",
            "weight" => "weight",
            "height" => "height",
            "smoke" => "smoke",
            "drugs" => "drugs",
            "major_occupation" => "occupation_major",
            "talents" => "talents",
            "favorite_food" => "favorite_food",
            "favorite_songs" => "favorite_songs",
            "favorite_books" => "favorite_books",
            "favorite_movies" => "favorite_movies",
            "drink" => "drink",
            "pets" => "pets",
            "interest_hobbies" => "hobbies",
            "nickname" => "",
            "timezone" => "",
            "receive_newsletter" => "",
            "country" => "country_code",
            "bio" => "bio",
            "turned_on" => "turn_on",
            "turned_off" => "turn_off",
            "private_do" => "private_do",
            "private_dont" => "private_dont",
            "room_rules" => "rules",
            "display_name" => "display_name",
            //"default_billing_address" => "",
            //"default_shiping_address" => "",
            //"billing_first_name" => "",
            //"chips" => "",
            "measurments" => "",
            "languages" => "",
            "zodiac_sign" => "zodiac_sign",
            "eye_color" => "eye_color",
            "pubic_hair" => "pubic_hair",
            "default_profile_address" => "country_code",
            "default_gift_address" => "gift_country_code"

        );

        //make some custom values
        if (
            array_key_exists("birthday_year", $post)
            && array_key_exists("birthday_month", $post)
            && array_key_exists("birthday_day", $post)
        ) {
            $post['birthday'] = $post['birthday_year'] . '-' . $post['birthday_month'] . '-' . $post['birthday_day'];
            /* unset($post['birthday_year']);
             unset($post['birthday_month']);
             unset($post['birthday_day']);*/
        }
        if (array_key_exists("spoken_languages", $post)) {
            if (is_array($post["spoken_languages"]))
                $post["languages"] = trim(implode(',', $post["spoken_languages"]), ',');
        }

        $settingsRepo = $this->em->getRepository(\PerfectWeb\Core\Entity\ResourceValue::class);
        $countryCodesRepo = $this->em->getRepository("Application\\Entity\\CountryCodes");
        $countryProvinceRepo = $this->em->getRepository("Application\\Entity\\CountryProvince");
        $countryCityRepo = $this->em->getRepository("Application\\Entity\\CountryCity");



        //parse throw resources and set the values
        foreach ($resources as $resource) {
            $is = false;

            $postValue = '';
            if (array_key_exists($resource["key"], $keyMap)) {
                $keyP = $keyMap[$resource["key"]];
                if (array_key_exists($keyP, $post)) {
                    $is = true;
                    $postValue = $post[$keyP];
                }
            }

            if ($is) {


                if (!empty($resource["entity"])) {


                    //$value = $resource['value'];

                    $entityName = 'Application\Entity\\' . $resource["entity"];
                    $repo = $this->em->getRepository($entityName);

                    if ($resource['entity'] == 'Address') {

                        if ($resource['value']->getAddressId()) {

                            $valueEntity = $repo->find($resource['value']);
                        }
                    } else {

                        $valueEntity = $repo->find($resource['value']);
                    }


                    if (!$valueEntity) {
                        $valueEntity = new $entityName();
                    }

                    //trim($post["gift_city_code"])); //(array("cityCode" => $post["gift_city_code"])));
                    //@todo: region and city. make address entity use countries table
                    if ($valueEntity) {

                        if ($resource['entity'] == "Address") {


                            $giftAddress = strpos($resource["key"], "gift");


                            if ($giftAddress !== false) {

                                /*      if ($post["gift_country_code"]) {
                                             $valueEntity->setCountryReference($countryCodesRepo->find($post["gift_country_code"]));
                                         }
                                         if ($post["gift_region_code"]) {
                                             $valueEntity->setProvinceReference($countryProvinceRepo->find($post["gift_region_code"]));
                                         }*/
                                /*             if ($post["gift_city_code"]) {
                                                 $valueEntity->setCityReference($countryCityRepo->find($post["gift_city_code"]));
                                             }*/

                                //gift country
                                if (isset($post["gift_country_code"])
                                    //  && !empty($post["gift_country_code"])
                                ) {
                                    $valueEntity->setCountry($countryCodesRepo->find($post["gift_country_code"]));
                                }

                                //gift region
                                if (isset($post["gift_region_code"])
                                    //  && !empty($post["gift_region_code"])
                                ) {
                                    $valueEntity->setProvince($countryProvinceRepo->find($post["gift_region_code"]));
                                }
                                //gift street address
                                if (isset($post["address"])
                                    // && !empty($post["gift_address"])
                                ) {
                                    $valueEntity->setStreet($post["address"]);
                                } //gift street address
                                if (isset($post["gift_city"])
                                    // && !empty($post["gift_address"])
                                ) {
                                    $valueEntity->setCity($countryCityRepo->find($post["gift_city_code"]));
                                }
                                //gift zip code
                                if (isset($post["gift_zip"])
                                    //&& !empty($post["gift_zip"])
                                ) {
                                    $valueEntity->setPostal($post["gift_zip"]);
                                }

                            } else {

                                /*         if ($post["country_code"]) {
                                             $valueEntity->setCountryReference($countryCodesRepo->find($post["country_code"]));
                                         }
                                         if ($post["region_code"]) {
                                             $valueEntity->setProvinceReference($countryProvinceRepo->find($post["region_code"]));
                                         }*/
                                /*            if ($post["city_code"]) {
                                                $valueEntity->setCityReference($countryCityRepo->find($post["city_code"]));
                                            }*/


                                //country
                                if (isset($post["country_code"])
                                    // && !empty($post["country_code"])
                                )
                                    $valueEntity->setCountry($countryCodesRepo->find($post["country_code"]));
                                //region
                                if (isset($post["region_code"])
                                    //&& !empty($post["region_code"])
                                )
                                    $valueEntity->setProvince($countryProvinceRepo->find($post["region_code"]));

                                //city
                                if (isset($post["city_code"])
                                    //&& !empty($post["city"])
                                ) {
                                    $valueEntity->setCity($countryCityRepo->find($post["city_code"]));
                                }
                                //street address
                                if (isset($post["address_real"])
                                    //&& !empty($post["address_real"])
                                ) {
                                    $valueEntity->setStreet($post["address_real"]);
                                }
                                //zip code
                                if (isset($post["zip_code"])
                                    //  && !empty($post["zip_code"])
                                ) {
                                    $valueEntity->setPostal($post["zip_code"]);
                                }
                            }

                            $valueEntity->setUserReference($userEntity);
                            // ~r($valueEntity);
                            $this->em->persist($valueEntity);
                            $this->em->flush();
                            /*    if ($resource["key"] == "default_profile_address") {
                                    r($post);
                                    ~r($valueEntity);
                                }*/

                            // $userEntity[0]->getAddressResources()->add($valueEntity);

                            //  $this->em->persist($userEntity[0]);
                            //  $this->em->flush();

                            $postValue = $valueEntity->getAddressId();


                        }
                        if ($resource['entity'] == "CountryCodes") {
                            $postValue = $valueEntity->getCountryCode();
                        }
                    }

                    unset($valueEntity);

                }
                $value = $settingsRepo->findOneById($resource["id"]);

                if ($value) {
                    $value->setValue($postValue);
                    $this->em->persist($value);

                }
            }
        }

        $this->em->flush();
        unset($userEntity);
    }

    /**
     * @throws Zend_Exception
     */
    public function profilesettingsAction()
    {
        $this->em = Zend_Registry::get('service_manager')->get('Doctrine\ORM\EntityManager');

        $this->load('info');
        $this->load('countries');
        $this->load('model_info');
        $this->load('model');
        $this->load('categories');


        $this->_data['info_fields'] = $this->model_info->getInfoByModel($_SESSION['user']['user_id']);

        // $this->_data['countries'] = $this->countries->fetchAll();

        $this->_data['categories'] = $this->categories
                                            ->fetchAll(
                                                $this->categories
                                                ->select()
                                                ->where("entity='".UserCategory::class."'")
                                            );

        //$this->_data['model'] = $this->model->getModel((int)$_SESSION['user']['user_id']);

        $this->_data['model'] = $this->em->getRepository(\Application\Entity\User::class)->findOneBy(array('id' => (int)$_SESSION['user']['user_id']));

        $this->_data['cover'] = $this->em->getRepository(\Images\Entity\UserImage::class)->findOneBy(array('id' => (int)$this->_data['model']->getIdCover()));

        $this->_data['cats_to_model'] = $this->categories->getCategoriesByModel($this->_data['model']->getId());

        if ($this->request->isPost()) {

            $post = $this->_request->getPost();

            if ($this->_request->save == 'Save') {
                //save to zf2
                $this->hydratePostToEntity($post);
            }
            //save 2 bottom page
            if ($this->_request->save2 == 'Save') { // More information about me
                $this->load("info");
                $infoFields = $this->info->getArrayFields();

                //make key value array from post to send to hydrator
                $postKeys = array();
                foreach ($infoFields as $k => $v) {
                    if (array_key_exists($k, $post)) {
                        $postKeys[$v] = $post[$k];
                        //unset($post[$k]);
                    }
                }
                //save to zf2
                $this->hydratePostToEntity($postKeys);

            }
            $current_filename = $this->_data['model']->getFilename();
            $current_cover = $this->_data['model']->getCover();

            if ($this->_request->save == 'Save') { // Main profile

                $userProfileFolder = '/uploads/users/'.$this->_data["model"]->getId().'/profile/';
                $photo_dir = APPLICATION_PATH . '/../../public'.$userProfileFolder;

                if (isset($_FILES['cover']) && is_uploaded_file($_FILES['cover']['tmp_name'])) {

                    $this->load('upload');

                    $upload = $this->upload->uploadPhoto($photo_dir);

                    if ($upload['status'] == 'success') {
                        $filename = $upload['file'];

                        $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 260, 190);

                        if ($current_cover) {

                            //delete old cover file and remove from db
                            db()->query("delete from photos where " . db()->quoteInto("id=?", $current_cover));
                            unlink($photo_dir . $current_filename);

                        }

                        //save new  cover in db tbl photos,

                        $user = $this->em->getRepository('\Application\Entity\User')->findOneBy(array('id' => $_SESSION['user']['id']));
                        $cover = new \Images\Entity\UserImage();
                        $cover->setUser($user);
                        $cover->setFilename($userProfileFolder.'/'.$filename);
                        $cover->setType($cover::COVER);
                        $cover->setStatus(1);
                        $cover->setEntityReference(new \Images\Entity\UserImage());

                        $this->em->persist($cover);
                        $this->em->flush();

                        //update db tlb model --> id_cover
                        //$post['id_cover'] = $cover->getId();


                    } else {
                        $photo_upload_failed = "<br>" . $upload['message'];
                    }
                } else {
                    //delete cover
                    if ($post['delete_cover']) {
                        $current_cover = $this->_data['model']->getCover();

                        if ($current_cover) {
                            db()->query("delete from photos where " . db()->quoteInto("id=?", $current_cover));
                            unlink($photo_dir . $current_filename);

                        }
                    }
                }

                $post['birthday'] = $post['birthday_year'] . '-' . $post['birthday_month'] . '-' . $post['birthday_day'];
                unset($post['birthday_year']);
                unset($post['birthday_month']);
                unset($post['birthday_day']);
                unset($post['save']);

                if ($post['screen_name'] != $this->_data['model']->screen_name) {
                    $post['new_screen_name'] = $post['screen_name'];

                    $addNotification = array(
                        "id_from" => $_SESSION["user"]["id"],
                        "type_from" => $_SESSION["group"],
                        "id_to" => $this->_data["model"]->assigned_to,
                        "type_to" => "moderator",
                        "type" => "screen_name",
                        "notification" => "Performer screen name changed from " . $this->_data["model"]->screen_name . " to " . $post["new_screen_name"] . " - waiting approval",
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "date" => time(),
                        "resource" => $this->_data["model"]->getId()
                    );

                    /*$this->addNotification($addNotification, "admin");
                    $this->addNotification($addNotification, "moderator");*/

                }

                unset($post['screen_name']);
                unset($post['chips']);

                unset($post['delete_cover']);

                $this->categories->addCategoryForModel(
                    $this->_data['model']->getId(),
                    array(
                        $post['category'],
                        $post['category1'],
                        $post['category2']
                    )
                );

                unset($post['category']);
                unset($post['category1']);
                unset($post['category2']);

                unset($post['country_name']);
                unset($post['region_name']);
                unset($post['city_name']);


                $message = "Main profile has been successfully saved!" . $photo_upload_failed;


                /* bad words filters */
                $this->load("bad_words");
                $badWords = $this->bad_words->getAllArray();
                array_walk($post, 'badWords', $badWords);
                /* end bad words filter */

                $this->model->update($post, db()->quoteInto("id=?", $_SESSION['user']['id']));

                $model = $this->model->find((int)$_SESSION['user']['id'])->current();

                $_SESSION['user']['user_photo'] = $model->getCover();

                $this->_helper->FlashMessenger->addMessage(notice($message));
            }

            if ($this->_request->save == 'Save') {
            // More information about me

                unset($post['save2']);

                /* bad words filters */
                $this->load("bad_words");
                $badWords = $this->bad_words->getAllArray();
                array_walk($post, 'badWords', $badWords);
                /* end bad words filter */

                foreach ($post as $key => $val) {

                    $_info = $this->model_info->getModelInfoById($_SESSION['user']['id'], $key);


                    if (is_array($val)) $val = implode(",", $val);
                    if (!$val || $val == '') { //if the field is empty delete from db if it exists

                        //$this->model_info->delete("id_model=" . $_SESSION['user']['id'] . " and id_field=" . $key);

                    } else {

                        if ($_info->id_field) { //we have previous values

                            $this->model_info->update(array("value" => $val), "id_model=" . $_SESSION['user']['id'] . " and id_field=" . $key);

                        } else { //we insert new value

                            $this->model_info->insert(array("value" => $val, "id_model" => $_SESSION['user']['id'], "id_field" => $key));
                        }
                    }
                }
                $message = "Additional Information successfully saved!";

                $this->load("notifications");
                $this->notifications->addNotification(user()->id, "model", user()->id, "model", "model-profile-edit", $message, 1, getUserIp());

                $this->_helper->FlashMessenger->addMessage(notice("Information successfully saved!"));
            }

            $this->_redirect($this->view->url(array(), 'model-profile-settings'));
        }

    }

    /**
     * @throws Zend_Exception
     */
    public function broadcastAction()
    {

        $this->load("model");
        //$this->load("chat");
        $this->load("user_settings");
        $this->load("webchat_users");
        $this->load("model_quotes");

        $this->_data['model'] = user();
        if ($this->request->isPost()) {
            $post = $this->_request->getPost();

            $_SESSION["broadcastMode"] = $post["broadcastMode"];

            if (isset($post["show_mode"])) {
                $_SESSION["user"]["chat_type"] = "show";
                $this->model->update(array("chat_type" => "show"), new Zend_Db_Expr("id=" . (int)user()->id));
            } elseif (isset($post["group_chat"])) {
                $_SESSION["user"]["chat_type"] = "group";
                $this->model->update(array("chat_type" => "group"), new Zend_Db_Expr("id=" . (int)user()->id));
            } else {
                $_SESSION["user"]["chat_type"] = "normal";
                $this->model->update(array("chat_type" => "normal"), new Zend_Db_Expr("id=" . (int)user()->id));
            }
        }


        if (isset($_SESSION["broadcastMode"]))
            $this->_data["selectBroadcast"] = $_SESSION["broadcastMode"];

        $this->_data['model'] = $this->model->getModel(user()->id);
        $this->_data['quotes'] = $this->model_quotes->quoteSuggest();

        if ($this->_request->start_private) {
            $this->model->update(array("chat_type" => "private"), new Zend_Db_Expr("id=" . $this->_data["model"]->id));
            $this->_data["model"]->chat_type = "private";
            user()->chat_type = "private";
            $_SESSION["user_chat"][$this->_data["model"]->id]["chat_type"] = "private";
        }

        $_SESSION['user']['chat_type'] = $this->_data['model']->chat_type;

        if ($this->_data['model']['chat_type'] != 'normal') {
            /*$user = $this->webchat_users->hasUser($this->_data['model']['id'], $this->_data['model']['chat_type']);
            if ($user) $user = $user->toArray();
            $this->_data['user'] = $user;*/
        }

        $sounds = $this->user_settings->getFieldsByUser(user()->id, "model", "sounds");
        if ($sounds) $sounds = $sounds->toArray();

        $sounds_list = array();
        foreach ($sounds as $sound) {
            $sounds_list[$sound['type']] = $sound['value'];
        }

        $this->_data['sounds'] = json_encode($sounds_list);

        if (Auth::isModel()) {
            //unset( $_SESSION['broadcastMode']);
            //$logged = $this->chat->checkLogged(user()->id);

            $this->load("webchat_sessions");
            $webchatSession = $this->webchat_sessions->getSession(user()->id);

            //$rtmp = "rtmfp://p2p.rtmfp.net/f052f4a46c53c1d9b71513b4-5a97f0c7d21e/".$channelName;
            $rtmp = config()->rtmp;
            $_SESSION['rtmp'] = $rtmp;


            //  re set streams for refresh/accepting requests
            if ($this->_data['model']['chat_type'] != "normal" && $webchatSession) {
                //set rtmp stream

                unset($_SESSION['streams']["model"]);

                for ($i = 1; $i <= $webchatSession->cameras; $i++) {
                    if (user()->chat_type != "normal") {
                        $_SESSION['streams']["model"][] = $this->_data['model']->getStream($webchatSession->id_user, $i);
                    } else {
                        $_SESSION['streams']["model"][] = $this->_data['model']->getStream(null, $i);
                    }
                }

            }

            if ($this->request->isPost()) {
                $post = $this->_request->getPost();

                if ($this->_request->startBroadcast) {

                    //set broadcast quality
                    if (isset($post["quality"]) && $this->acl->isAllowed($_SESSION['group'], "broadcast-quality", "view"))
                        $quality = "hd";
                    else
                        $quality = "sd";

                    if ($post["cameras_number"]) {
                        if ($post["cameras_number"] > 3)
                            $cameras = 3;
                        else
                            $cameras = (int)$post["cameras_number"];

                    } else
                        $cameras = 1;

                    if ($cameras > 3) $cameras = 3;

                    $this->_data["cameras_number"] = $cameras;

                    //set rtmp stream
                    unset($_SESSION['streams']["model"]);

                    for ($i = 1; $i <= $cameras; $i++) {
                        if (user()->chat_type != "normal") {
                            $_SESSION['streams']["model"][] = $this->_data['model']->getStream($webchatSession->id_user, $i);
                        } else {
                            $_SESSION['streams']["model"][] = $this->_data['model']->getStream(null, $i);
                        }
                    }

                    if(user()->screen_name) {
                        $chatName = user()->screen_name;
                    } else if(user()->screen_name){
                        if(is_aray(user()->display_name) && array_key_exists('value', user()->display_name)){
                            $chatName = user()->display_name['value'];
                        } else {
                            $chatName = user()->display_name;
                        }
                    } else {
                        $chatName = (user()->username ? user()->username : 'performer' . user()->id);
                    }

                    $this->_data["selectBroadcast"] = $post["broadcastMode"];

                    //set broadcast mode for rtmp & webchat_users
                    $_SESSION["user_chat"][user()->id]["broadcastMode"] = $post["broadcastMode"];
                    //$_SESSION['broadcastMode'] = $post["broadcastMode"];
                    // $_SESSION['autostart'] = "true";
                    $_SESSION["user_chat"][user()->id]['autostart'] = "true";
                    // $_SESSION["quality"] =
                    $_SESSION["user_chat"][user()->id]["quality"] = $quality;

                    //init/update chat session
                    $this->load("webchat_sessions");
                    $this->webchat_sessions->saveSession(
                        user()->id,
                        (user()->chat_type ? user()->chat_type : 'normal'),
                        null,
                        $cameras
                    );

                    $this->_data['timer'] = time() - $webchatSession->timer;

                    $notice_msq = "Model " . user()->screen_name . " is online!";

                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "model", 0, "moderator", "model_broadcast", (user()->id == 0 ? "admin - " . $notice_msq : "moderator - " . $notice_msq), 1, getUserIp());

                    $addNotification = array(
                        "id_from" => $_SESSION["user"]["id"],
                        "type_from" => $_SESSION["group"],
                        "id_to" => $this->_data["model"]->assigned_to,
                        "type_to" => "moderator",
                        "type" => "model_online",
                        "notification" => "Performer " . $this->_data["model"]->screen_name . " is broadcasting now ",
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "date" => time(),
                        "resource" => $_SESSION["user"]["id"]
                    );

                    $this->addNotification($addNotification, "admin");

                    // add model action for followers notifications
                    $this->load("model_actions");
                    $this->model_actions->actionAdd("online", $this->_data["model"]->id);

                    if ($this->_data["model"]->assigned_to > 0)
                        $this->addNotification($addNotification, "moderator");

                    $this->_redirect($this->view->url(array("controller" => "model", "action" => "broadcast")));
                }
            }

            //view user webcam
            if ($this->_data['model']['chat_type'] == 'private' || $this->_data['model']['chat_type'] == 'vip') {
                $webchatUserSession = $this->webchat_users->getPrivateUserSession($_SESSION["user"]["id"]);
                $this->load("user");

                $iduser = (int)str_replace("user_", "", $webchatUserSession->id_user);
                $user = $this->user->getUserById($iduser);
                if ($user)
                    $this->_data["stream_user"] = $user->getStream($iduser);

            }

        }
    }

    /**
     *
     */
    public function comingAction()
    {

    }

    /**
     *
     */
    public function modelsAction()
    {

    }

    /**
     *
     */
    public function lobbyAction()
    {

    }

    /**
     *
     */
    public function restrictAction()
    {

    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function privacysettingsAction()
    {

        /*if (!$this->acl->isAllowed($_SESSION['group'], "model-privacy-settings", "view")) return $this->_forward("restrict");*/

        $this->load("model_block_access");
        $this->load('countries');
        $this->load('webcam_access');
        $this->load('user_settings');
        $this->load('model');

        $this->_data['model'] = $this->model->getModel((int)$_SESSION['user']['id']);

        $this->_data['countries'] = $this->countries->fetchAllLocations('co');

        $this->_data['webcam_rules'] = $rules = $this->webcam_access->fetchAll();

        $model_rules = $this->user_settings->getFieldsByUser($_SESSION['user']['id'], "model", "webcam_access");
        if (count($model_rules) > 0) {
            foreach ($model_rules as $rule) {
                $this->_data['model_webcam_rules'][$rule->type] = $rule->value;
            }
        } else {
            $this->_data['model_webcam_rules'] = array();
        }

        if ($this->_request->type == 'general') {

            $this->_data['current_rule'] = $this->model_block_access->getRule($this->_request->id);

        }

        if ($this->_request->type == 'list') {

            $this->load("model_access");
            $this->load("model_user_access");
            $nr = 20;
            $post = $this->_request->getParams();

            $page = 1;
            if (isset($post['page'])) {
                $page = $post['page'];
                unset($post['page']);
            }

            $paginator = Zend_Paginator::factory($this->model_user_access->getFieldsByModel(user()->id));
            $paginator->setItemCountPerPage($nr);
            $paginator->setCurrentPageNumber($page);
            $this->view->paginator = $paginator;

        }

        if ($this->_request->type == 'user') {

            if ($this->_request->id || true) {

                $this->load("model_access");
                $this->load("model_user_access");
                $this->_data['user_access'] = $this->model_user_access->getFieldsByModel(user()->id, /*$this->_request->id*/
                    1, true);
            }
        }

        if ($this->_request->type == 'global' || !$this->_request->type) {

            if ($this->_request->type == 'global') {
                $nr = 20;
                $post = $this->_request->getParams();

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }

                $paginator = Zend_Paginator::factory($this->model_block_access->getAccessRules(user()->id));
                $paginator->setItemCountPerPage($nr);
                $paginator->setCurrentPageNumber($page);
                $this->view->paginator = $paginator;
            } else {
                $this->_data['block_rules'] = $this->model_block_access->getAccessRules(user()->id);
            }

        }

        if ($this->_request->type == 'general') {
            $this->_data['block_rules'] = $this->model_block_access->getAccessRules(user()->id, $this->_data['current_rule']->id_country, $this->_data['current_rule']->state, $this->_data['current_rule']->city);
        }

        // Delete a block rule
        if ($this->_request->manage == 'delete') {

            $id = $this->_request->id;

            $this->db->delete("model_block_access", $this->db->quoteInto("id=?", $id));

            $message = "Access rule successfully deleted!";

            $this->load("notifications");
            $this->notifications->addNotification($_SESSION['user']['id'], "model", $_SESSION['user']['id'], "model", "model-privacy-settings-delete", $message, 1, getUserIp());
            $this->_helper->FlashMessenger->addMessage(notice($message));

            $this->_redirect($this->view->url(array(), 'model-privacy-settings'));

        }

        if ($this->request->isPost()) {
            $post = $this->_request->getPost();


            // Save a new block rule
            if ($this->_request->save == 'Add') {

                unset($post['save']);
                $post['id_model'] = $_SESSION['user']['id'];
                $post['id_country'] = $this->countries->getIdFromCode($post['id_country']);
                $post['city'] = stripText($post['city']);
                $post['state'] = stripText($post['state']);

                $this->model_block_access->insert($post);

                $message = "Access rule successfully saved!";

                $this->load("notifications");
                $this->notifications->addNotification($_SESSION['user']['id'], "model", $_SESSION['user']['id'], "model", "model-privacy-settings-add", $message, 1, getUserIp());
                $this->_helper->FlashMessenger->addMessage(notice($message));
            }

            if (isset($post["guestbook"])) {
                $status = $post['guestbook'];

                $this->_data["model"]->setGuestbookStatus($status);
                $addNotification = array(
                    "id_from" => $_SESSION["user"]["id"],
                    "type_from" => $_SESSION["group"],
                    "id_to" => $this->_data['model']->id,
                    "type_to" => "model",
                    "type" => "guestbook",
                    "notification" => "Moderator " . $_SESSION["user"]["screen_name"] . " has changed your guestbook status!",
                    "ip" => $_SERVER["REMOTE_ADDR"],
                    "date" => time(),
                    "resource" => 0
                );
                $this->addNotification($addNotification, "model");
            }

            // Edit a block rule
            if ($this->_request->edit == 'Save') {

                unset($post['edit']);
                $id = $post['id'];
                unset($post['id']);

                $post['id_model'] = $_SESSION['user']['id'];
                $post['id_country'] = $this->countries->getIdFromCode($post['id_country']);
                $post['city'] = stripText($post['city']);
                $post['state'] = stripText($post['state']);

                $this->model_block_access->update($post, $this->db->quoteInto("id=?", $id));

                $message = "Access rule successfully saved!";

                $this->load("notifications");
                $this->notifications->addNotification($_SESSION['user']['id'], "model", $_SESSION['user']['id'], "model", "model-privacy-settings-edit", $message, 1, getUserIp());
                $this->_helper->FlashMessenger->addMessage(notice($message));

                $this->_redirect($this->view->url(array("type" => 'general', "id" => $id, "manage" => 'edit'), 'model-privacy-settings-manage'));

            }

            //Global webcam settings
            if ($this->_request->save_webcam == 'Save') {

                unset($post['save_webcam']);
                $id_model = $_SESSION['user']['id'];

                foreach ($rules as $key => $value) {
                    $id_field = $value['id'];
                    $setting = $this->_data['model_webcam_rules'][$value['type']];

                    if ($post[$value['type']] == 1) {
                        $allow = 1;
                    } else {
                        $allow = 0;
                    }

                    if ($setting !== false && !is_null($setting)) { //update
                        $row = array(
                            "value" => $allow
                        );

                        $this->user_settings->update($row, $this->db->quoteInto("id_user=?", $id_model) . " and " . $this->db->quoteInto("user_type=?", "model") . " and " . $this->db->quoteInto("id_field=?", $id_field) . " and " . $this->db->quoteInto("setting_type=?", 'webcam_access'));

                    } else { //insert
                        $row = array(
                            "id_user" => $id_model,
                            "id_field" => $id_field,
                            "user_type" => "model",
                            "setting_type" => 'webcam_access',
                            "value" => $allow
                        );

                        $this->user_settings->insert($row);
                    }

                }
                $message = "Privacy settings successfully saved!";

                $this->load("notifications");
                $this->notifications->addNotification($_SESSION['user']['id'], "model", $_SESSION['user']['id'], "model", "model-privacy-settings-webcam", $message, 1, getUserIp());
                $this->_helper->FlashMessenger->addMessage(notice($message));
            }

            if ($this->_request->save_user == 'Save') {
                //@todo save settings!!!!!!!!
            }

            $this->_redirect($this->view->url(array(), 'model-privacy-settings'));
        }

    }

    /**
     * @throws Zend_Exception
     */
    public function chatsettingsAction()
    {

        $this->load('model');
        $this->load('model_rates');
        $this->load('model_rates_pending');
        $this->load('rates');
        $this->load('sounds');
        $this->load("user_settings");
        $this->load('rates_limits');

        $this->_data['sounds_fields'] =
            $this->db->select()->from('resources')->where('group_name="chat-sounds" and context="performer.cfg"')->query()
                ->fetchAll();

        $this->_data['rates_fields'] = $this->model_rates->getRatesByModel($_SESSION['user']['id']);
        $this->_data['model'] = $this->model->find((int)$_SESSION['user']['id'])->current();

        if ($this->request->isPost()) {
            $post = $this->_request->getPost();


            if ($this->_request->raise_limits == 'Save rates') { // Request new rates
                unset($post['raise_limits']);
                $limits_breach = false;

                //save special rates request
                if ($post['special_rate'] != 0) {
                    $key = $post['special_rate'];
                    $val = $post['special_rate_value'];

                    $_rates = $this->model_rates_pending->getModelRateById($_SESSION['user']['id'], $key);

                    //check limits
                    $rate_max = $this->rates_limits->getLimit($key, 'max');

                    if ($val > $rate_max->value) {
                        if ($_rates->id_rate) { //request pending for rate


                            if ($_rates->value != $val) { //update with new value if != model_rates
                                db()->query("update model_rates_pending set " . db()->quoteInto("value=?", $val) . " where " . db()->quoteInto("id_model=?", $_SESSION['user']['id']) . " and " . db()->quoteInto("id_rate=?", $key));
                            }


                        } else { //no request sent yet

                            db()->query("insert into model_rates_pending set " . db()->quoteInto("value=?", $val) . ", " . db()->quoteInto("id_model=?", $_SESSION['user']['id']) . "," . db()->quoteInto("id_rate=?", $key));


                        }
                        $message = "Special rates requested!";

                        $this->load("notifications");
                        $this->notifications->addNotification(user()->id, "model", user()->id, "model", "model-rates-edit", $message, 1, getUserIp());

                    } else {
                        $limits_breach = true;

                    }
                }
                unset($post['special_rate']);
                unset($post['special_rate_value']);


                foreach ($post as $key => $val) {

                    $_rates = $this->model_rates->getModelRateById($_SESSION['user']['id'], $key);

                    //check limits
                    $rate_min = $this->rates_limits->getLimit($key, 'min');
                    $rate_max = $this->rates_limits->getLimit($key, 'max');

                    if ($val >= $rate_min->value && $val <= ($_rates->special ? ($_rates->special != 0 ? $_rates->special : $rate_max->value) : $rate_max->value)) {
                        if ($_rates->id_rate) { //request pending for rate


                            if ($_rates->value != $val) { //update with new value if != model_rates
                                db()->query("update model_rates set " . db()->quoteInto("value=?", $val) . " where " . db()->quoteInto("id_model=?", $_SESSION['user']['id']) . " and " . db()->quoteInto("id_rate=?", $key));
                            }


                        } else { //no request sent yet

                            db()->query("insert into model_rates set " . db()->quoteInto("value=?", $val) . ", " . db()->quoteInto("id_model=?", $_SESSION['user']['id']) . "," . db()->quoteInto("id_rate=?", $key));


                        }


                    } else {
                        $limits_breach = true;

                    }


                }
                if ($limits_breach) {
                    $this->_helper->FlashMessenger->addMessage(notice("Limits not respected! Rates not saved!"));
                } else {
                    $message = "New rates saved!";

                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "model", user()->id, "model", "model-rates-edit", $message, 1, getUserIp());
                    $this->_helper->FlashMessenger->addMessage(notice($message));
                }
            }

            if ($this->_request->save2 == 'Save') { // Sounds

                unset($post['save2']);

                foreach ($post as $key_temp => $val) {
                    $key = substr($key_temp, strpos($key_temp, "_") + 1);

                    $resource = current($this->db->select()->from('resources')->where('group_name="chat-sounds" and name="'.$key.'"')
                            ->query()->fetchAll());

                    $this->db->delete('resource_values', "user_id=" . $_SESSION['user']['id'] . " and resource_id=".$resource['id']);

                    if (!$val || $val == '' || $val == '0') { //if the field is empty delete from db if it exists
                        $this->db->delete('resource_values', "user_id=" . $_SESSION['user']['id'] . " and
                        resource_id=".$resource['id']);
                    }
                    else {

                     //we insert new value
                        $this->db->insert('resource_values',
                          array("value" => $val, "user_id" => $_SESSION['user']['id'], "resource_id" =>
                              $resource['id'])
                        );
                    }
                }

                $this->_helper->FlashMessenger->addMessage(notice("Sound settings successfully saved"));
            }

            $this->_redirect('/admin/chat/sounds');
        }
    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function trainautorespondersAction()
    {

        $this->load('autoresponders_train');

        $nr = 10;
        $post = $this->params;

        unset($post['controller']);
        unset($post['action']);
        unset($post['module']);

        $page = 1;
        if (isset($post['page'])) {
            $page = $post['page'];
            unset($post['page']);
        }

        $paginator = Zend_Paginator::factory($this->autoresponders_train->getAutorespondersByModel($_SESSION['user']['id']));
        //set count per page
        $paginator->setItemCountPerPage($nr);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

    }

    /**
     *
     */
    public function musicsettingsAction()
    {

    }



    /**
     *
     */
    public function phonesettingsAction()
    {

    }

    /**
     * @throws Zend_Exception
     */
    public function uploadAction()
    {
        //path to upload folder, switched from zf1 to zf2
        $uploadBasePath = realpath(APPLICATION_PATH . '/../../public/' . config()->default_upload_dir);

        $this->_data['type'] = 'uploadMedia';
        $this->_data['params']['type'] = $this->_request->type;
        switch ($this->_request->type) {

            case "photo":

                $this->load('albums');
                $this->load('photos');
                $this->load('model');
                $this->_data['albums'] = $this->albums->getAlbums($_SESSION['user']['id'], false, false);
                $this->_data['model'] = $this->model->getById($_SESSION['user']['id']);
                if ($this->_data['model']->auto_approve == 1) {
                    $active = 1;
                } else {
                    $active = 0;
                }

                if ($this->_request->isPost()) {

                    $post = $this->_request->getPost();

                    if (!isset($_SESSION["token"]) || $_SESSION["token"] != $post["token"]) {

                        if ($post['existing_gallery'] == 0 && !$_SESSION['upload_album']['count_success']) {

                            $album_add = array(
                                "model_id" => $_SESSION['user']['id'],
                                "name" => !$post['name'] ? "Untitled" : $post['name'],
                                "description" => '' . $post['description'],
                                "tags" => $post['tags'],
                                //"total_photos" => $post['total_photos'],
                                // "password_required" => $post['password_required'],
                                "password" => $post['password'],
                                "cost" => (int)$post['amount'],
                                //"viewable" => date('Y-m-d H:i:s', strtotime($post['viewable'])),
                                //"parent_id" => (int)$post['parent_id'],
                                "uploaded_on" => date('Y-m-d H:i:s'),
                                "updated" => time(),
                                "status" => $active,
                            );

                            if ($post["type_resource"] == "pledge") {
                                $album_add["type"] = "pledge";
                            }
                            $_SESSION['upload_album']['reference_id'] = $this->albums->insertItems($album_add);

                        }
                        elseif ($post['existing_gallery'] > 0)  {
                            //use existing album
                            //@todo should check if $post['existing_gallery'] (id album) belongs to the logged in model
                            $_SESSION['upload_album']['reference_id'] = $post['existing_gallery'];

                        }

                    }

                    $photo_dir = 'users/'.$_SESSION['user']['id'].'/photos/';
                    $this->load('upload');

                    $upload = $this->upload->uploadPhoto($uploadBasePath . "/" . $photo_dir, 'Filedata');

                    if ($upload['status'] == 'success') {

                        $filename = $upload['file'];
                        $path = $upload['path'];

                        $this->upload->resize_image($path, $path, 800, 600, config()->photo_watermark);
                        //$this->upload->create_square_image($photo_dir.$filename, $photo_dir.substr($filename,0,-4).'_t'.substr($filename,-4), 190);
                        //$this->upload->resize_image_proportional($photo_dir.$filename, $photo_dir.substr($filename,0,-4).'_tt'.substr($filename,-4), 190, 140);
                        /*$this->upload->resize_image_proportional($path . $filename, $path . "/" . substr($filename, 0, -4) . '_t' . substr($filename, -4), 190, 140, 1);*/

                        if ($_SESSION['upload_album']['reference_id'])  {
                            $album = $this->albums->getAlbum($_SESSION['upload_album']['reference_id']);
                        }

                        $null = new Zend_Db_Expr("NULL");
                        $photo_add = array(
                            "user" => $_SESSION['user']['id'],
                            "filename" => '/uploads/'.$photo_dir.$filename,
                            "reference_id" => $null,
                            "status" => Status::PENDING,
                            "caption" => $album['name'],
                            "entity" => \Images\Entity\Photo::class,
                            "uploaded_on" => date('Y-m-d H:i:s'),
                            "position" => (int)$_SESSION['upload_album']['count'],
                            'album_id' => $_SESSION['upload_album']['reference_id']
                        );

                        $lastPhotoId = $this->photos->insertItems($photo_add);

                        $_SESSION['upload_album']['count_success']++;

                        echo 'ok';

                    }
                    else {
                        if(isset($upload["status"]) && $upload["status"] == 'error') {
                            echo json_encode($upload);
                        }
                        else {
                            echo json_encode(array("status" => "error", "message" => "Error uploading image"));
                        }
                        exit;
                    }

                    $_SESSION['upload_album']['count']++;

                    if ($_SESSION['upload_album']['count'] == $post['total_photos']) {

                        //last photo, update album
                        if (!$post['existing_gallery']) {

                            $photo_add['album_id'] = $null;
                            $photo_add['status'] = Status::ACTIVE;
                            $photo_add['entity'] = \Images\Entity\AlbumCoverImage::class;

                            $this->albums->update(array(
                                "cover" => $this->photos->insertItems($photo_add)
                            ), "id=" . $_SESSION['upload_album']['reference_id']);

                        }

                        unset($_SESSION['upload_album']);

                    }

                    exit;

                }

                unset($_SESSION['upload_album']);

                break;

            case "video":
                $this->load("upload");

                if ($this->getRequest()->isPost()) {
                    $post = $this->_request->getPost();
                    // if(is_video($_FILES["upload_video"])) {
                    $video_dir = 'videos/';
                    $this->load('upload');

                    $upload = $this->upload->uploadVideo($uploadBasePath . '/' . $video_dir, 'upload_video');
                    if ($upload['status'] == 'success') {

                        $video["title"] = $post["title"];
                        $video["tags"] = $post["tags"];
                        $video["description"] = '' . $post["description"];
                        $video["filename"] = $filename = '/'.$upload['file'];
                        $video["user"] = $_SESSION["user"]["id"];
                        $video["uploaded_on"] = time();
                        $video["type"] = $post["type"];//FIXME fix this;
                        $video['entity'] = \Videos\Entity\PremiereVideo::class;
                        $video["status"] = \PerfectWeb\Core\Utils\Status::ACTIVE;

                        /* bad words filters */

                        $this->load("bad_words");
                        $badWords = $this->bad_words->getAllArray();
                        array_walk($video, 'badWords', $badWords);

                        /* end bad words filter */

                        $this->load("video");
                        if ($post["type"] == "bio") {
                            $select = $this->video->select()->where("id_model=?", user()->id)->where("type='bio'");
                            $bio = $this->video->fetchRow($select);
                            if ($bio) {
                                $path_file = realpath(APPLICATION_PATH . '/../../public/uploads/videos/' . $bio->filename);
                                if ($path_file) {
                                    @unlink($path_file);
                                }
                                $this->video->delete(db()->quoteInto("id=?", $bio->id));
                            }
                        }

                        $this->video->insert($video);

                        $this->load("model_actions");
                        $this->model_actions->actionAdd("new_video", user()->id);

                        $this->_helper->FlashMessenger->addMessage(notice("Video uploaded! It will be moderated soon"));

                        //move_uploaded_file($_FILES['upload_video']['tmp_name'], "");
                    }
                    /* else {
                        $this->_helper->FlashMessenger->addMessage(notice("Upload failed! \n" . $upload["message"], false));
                    }*/
                    else {
                        if (isset($upload["status"]) && $upload["status"] == 'error') {
                            echo json_encode($upload);
                        } else {
                            echo json_encode(array("status" => "error", "message" => "Error uploading file"));
                        }
                        exit;
                    }

                    //$this->_redirect('/performer/upload/video');
                    exit;
                }

                break;

        }

    }


    /**
     * @throws Zend_Exception
     */
    public function managephotoseditAction()
    {

        $this->load('albums');

        $this->_data['album'] = $this->albums->getAlbum($this->request->id);

        if ($this->request->isPost()) {
            $post = $this->_request->getPost();

            if (!trim($post['name'])) {
                $this->_helper->FlashMessenger->addMessage(notice('Please complete all fields', 1));
                $this->_redirect($this->view->url(array("type" => "photo_galeries", "id" => $this->_data['album']->id), "model-manage-photos2"));
            }

            unset($post['save']);
            //$post['viewable'] = strtotime($post['viewable']);

            $this->albums->update($post, $this->albums->getAdapter()->quoteInto("id=?", $this->_data['album']->id));
            $this->_helper->FlashMessenger->addMessage(notice("Gallery has been successfully saved!"));
            $this->_redirect($this->view->url(array("type" => "photo_galeries", "id" => $this->_data['album']->id), "model-manage-photos2"));
        }
    }

    /**
     *
     */
    public function managevideosAction()
    {
        /*if (!$this->acl->isAllowed($_SESSION['group'], "model-manage-photos", "view")) return $this->_forward("restrict");*/
        //FIXME @Alin ... nothing is done on zf1 so we will do it tomorrow on zf2

        $this->_data['type'] = 'videos';
        $this->_helper->viewRenderer('manage-videos');
    }

    /**
     *
     */
    public function managestoreAction()
    {

    }

    /**
     *
     */
    public function manageauctionAction()
    {

    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function manageBlogAction()
    {

        if (!$this->params["type_action"]) $this->_redirect("/404/");
        $this->load("blog_posts");

        if ($this->params["type_action"]) {

            switch ($this->params["type_action"]) {
                case "posts":

                    $this->_data["posts"] = $this->blog_posts->getAllPosts(array(), user()->id, false, false, false);

                    $nr = 10;
                    if (!isset($page)) $page = 1;
                    $paginator = Zend_Paginator::factory($this->_data["posts"]);
                    $paginator->setItemCountPerPage($nr);
                    $paginator->setCurrentPageNumber($page);
                    $this->view->paginator = $paginator;

                    break;
                case "post-edit":

                case "post-add":
                    $this->load("blog_categories");
                    $this->load("model_moderator");
                    $this->load("blog_posts");
                    $this->load("blog_access");

                    if ($this->params["type_action"] == "post-edit" && isset($this->params["id"])) {
                        $this->_data["article"] = $this->blog_posts->getById($this->params["id"], user()->id);

                        if (!$this->_data["article"]->id) $this->_redirect("/404/");
                        if ($this->_data["article"]->status == 1) $this->_redirect("/404/");

                        $this->_data["access"] = $this->blog_access->getByPostId($this->_data["article"]->id);
                        $id_post = $this->_data["article"]->id;
                    }

                    $id_model = $this->_data["article"]->id_model;

                    //$this->blog_posts->getAllPosts(array("members"));

                    $this->_data["categories"] = $this->blog_categories->getAllByModelArray($id_model);

                    $this->_data["moderator"] = $this->model_moderator->getModelModerator($id_model);

                    $request = $this->_request;
                    $post = $request->getPost();
                    if (isset($post["save_unfinished"]) || isset($post["save_moderation"])) {
                        if (!$post['title'] || !$post['content'] || empty($post['title']) || empty($post['content'])) {
                            $this->_helper->FlashMessenger->addMessage(notice('Please add title and content'));
                            $this->_redirect('/performer/blog/post-add');
                        }

                        $blogPost["title"] = $post["title"];
                        $blogPost["category"] = $post["category"];
                        $blogPost["createdby"] = $post["createdby"];
                        $blogPost["tags"] = $post["tags"];
                        $blogPost["content"] = $post["content"];
                        $blogPost["id_model"] = $id_model;
                        $blogPost["status"] = (isset($post["status"]) ? $post["status"] : "");
                        $blogPost["status"] = (isset($post["save_moderation"]) ? "2" : "0");
                        $blogPost["featured"] = (isset($post["featured"]) ? $post["featured"] : "0");
                        //save latter send for approval

                        if ($this->params["type_action"] == "post-add")
                            $id_post = $this->blog_posts->insert($blogPost);
                        else
                            $this->blog_posts->update($blogPost, "id=" . (int)$this->params["id"]);

                        if ($this->params["type_action"] == "post-edit" && $id_post) {
                            $this->blog_access->delete(new Zend_Db_Expr("id_post=" . $id_post));
                        }


                        if ($this->params["type_action"] == "post-add" || $this->params["type_action"] == "post-edit" && $id_post) {
                            $this->load("blog_access");
                            $this->blog_access->saveAccess($id_post, $post);
                        }

                        $photo_dir = APPLICATION_PATH . '/../../public/uploads/photos/';
                        $this->load('upload');

                        if (isset($_FILES["cover_image"]) && !empty($_FILES["cover_image"]["name"])) {


                            $upload = $this->upload->uploadPhoto($photo_dir, 'cover_image');

                            if ($upload['status'] == 'success') {
                                $filename = $upload['file'];
                                $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 800, 600, config()->photo_watermark);

                                db()->query("insert into photos set " . db()->quoteInto("id_model=?", $_SESSION['user']['id']) . ",
                                                                    " . db()->quoteInto("filename=?", $filename) . ", "
                                    . "reference_id='" . $id_post . "',
                                                                    active=1,
                                                                    type='post_small_cover'
                                                                    ");

                            } else {
                                echo 'error uploading file cover image!';
                            }
                        }
                        if (isset($_FILES["full_image"]) && !empty($_FILES["full_image"]["name"])) {
                            $upload2 = $this->upload->uploadPhoto($photo_dir, 'full_image');

                            if ($upload2['status'] == 'success') {
                                $filename = $upload2['file'];
                                $this->upload->resize_image($photo_dir . $filename, $photo_dir . $filename, 800, 600, config()->photo_watermark);

                                db()->query("insert into photos set " . db()->quoteInto("id_model=?", $_SESSION['user']['id']) . ",
                                                                    " . db()->quoteInto("filename=?", $filename) . ", "
                                    . "reference_id='" . $id_post . "',
                                                                    active=1,
                                                                    type='post_full_cover'
                                                                    ");

                            } else {
                                echo 'Error uploading file full image!';
                            }
                        }

                        if (isset($post["save_moderation"])) {

                            $message = "Performer " . (user()->screen_name) . " submited post: " . $blogPost["title"];
                            $this->load("notifications");
                            $this->notifications->addNotification(user()->id, "model", $_SESSION['user']['id'], 'user', "model-blogpost-add", $message, 1, getUserIp());

                            $this->_helper->FlashMessenger->addMessage(notice($message));

                            $addNotification = array(
                                "id_from" => $_SESSION['user']['id'],
                                "type_from" => $_SESSION['group'],
                                "id_to" => (user()->id_moderator ? user()->id_moderator : 0),
                                "type_to" => "moderator",
                                "type" => "blog_post",
                                "notification" => $message,
                                "ip" => $_SERVER["REMOTE_ADDR"],
                                "date" => time(),
                                "resource" => $id_post,
                                "linked_resource" => $_SESSION['user']['id'],
                            );
                            $this->addNotification($addNotification, "admin");
                            $this->addNotification($addNotification, "moderator");
                        }

                        $this->_helper->FlashMessenger->addMessage(notice("Post saved!"));
                        $this->_redirect($this->view->url(array("type_action" => "posts"), "model-manage-blog", true));
                    }
                    break;
                case "view":
                    $this->load("blog_posts");
                    $this->load("blog_access");
                    $this->_data["article"] = $this->blog_posts->getById((int)$this->params["id_item"]);
                    $this->_data["access"] = $this->blog_access->getByPostId($this->_data["article"]->id);
                    break;
                case "categories":
                    $this->load("blog_categories");
                    $request = $this->_request;
                    $post = $request->getPost();
                    if ($post) {
                        $this->blog_categories->deleteMultiple($post["multiple_select"]);
                    }

                    $this->_data["categories"] = $this->blog_categories->getByModelId(user()->id);


                    break;
                case "category-add":
                    $this->load("blog_categories");

                    $request = $this->_request;
                    $post = $request->getPost();

                    if ($post) {

                        if (!$post['title'] || empty($post['title'])) {
                            $this->_helper->FlashMessenger->addMessage(notice('Please add title '));
                            $this->_redirect('/performer/blog/category-add');
                        }

                        $cat = array();
                        $cat["title"] = $post["title"];
                        $cat["parent_id"] = $post["parent_id"];
                        $cat["id_model"] = user()->id;

                        $this->blog_categories->insert($cat);
                        $this->_helper->FlashMessenger->addMessage(notice('Category added'));
                        $this->_redirect('/performer/blog/category-add');
                    }

                    $this->_data["categories"] = $this->blog_categories->getAllByModelArray(user()->id);

                    break;
            }
        }
    }

    /**
     *
     */
    public function recordmediaAction()
    {
        /*if (!$this->acl->isAllowed($_SESSION['group'], "model-media-record", "edit")) return $this->_forward("restrict");*/
    }

    /**
     *
     */
    public function managebannersAction()
    {
    }

    /**
     *
     */
    public function managerefferalAction()
    {
    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function messagesAction()
    {

        if (!Auth::isModel()) $this->_redirect("/404/");

        $this->load('messages');
        $this->load('model');
        $this->load('user');
        $this->load('moderator');

        $this->_data['message_action'] = $this->_request->message_action;

        $post = $this->params;

        if ($this->_request->isPost() && $this->_request->message_action != "compose") {

            if (isset($post["archive"]))
                $this->messages->archiveMessages($post["multiple_select"]);
            if (isset($post["delete"]))
                $this->messages->deleteMessages($post["multiple_select"]);
            if (isset($post["read"]))
                $this->messages->updateMessages($post["multiple_select"], "1");
            if (isset($post["unread"]))
                $this->messages->updateMessages($post["multiple_select"], "0");
            /* refresh count */
            $this->_data['unread_count'] = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group']);
        }

        switch ($this->_request->message_action) {

            case "inbox":

                $this->_data['page_title'] = 'Inbox';
                $nr = 20;

                unset($post['controller']);
                unset($post['action']);
                unset($post['module']);

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }
                $users["moderator"] = $this->moderator->getNames();
                $users["user"] = $this->user->getNames();
                $users["model"] = $this->model->getNames();

                $this->_data["users"] = $users;

                $paginator = Zend_Paginator::factory($this->messages->getModelInbox($_SESSION['user']['id']));
                $paginator->setItemCountPerPage($nr);
                $paginator->setCurrentPageNumber($page);
                $this->view->paginator = $paginator;

                break;

            case "archive":

                $this->_data['page_title'] = 'Archive';
                $nr = 20;

                unset($post['controller']);
                unset($post['action']);
                unset($post['module']);

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }
                $users["moderator"] = $this->moderator->getNames();
                $users["user"] = $this->user->getNames();
                $users["model"] = $this->model->getNames();

                $count = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group']);
                $this->_data['unread_count'] = $count;

                $this->_data["users"] = $users;

                $paginator = Zend_Paginator::factory($this->messages->getModelArchive($_SESSION['user']['id']));
                $paginator->setItemCountPerPage($nr);
                $paginator->setCurrentPageNumber($page);
                $this->view->paginator = $paginator;

                break;

            case "outbox":

                $this->_data['page_title'] = 'Sent Messages';
                $nr = 20;

                unset($post['controller']);
                unset($post['action']);
                unset($post['module']);

                $users["moderator"] = $this->moderator->getNames();
                $users["user"] = $this->user->getNames();
                $users["model"] = $this->model->getNames();

                $count = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group']);
                $this->_data['unread_count'] = $count;

                $this->_data["users"] = $users;

                $page = 1;
                if (isset($post['page'])) {
                    $page = $post['page'];
                    unset($post['page']);
                }

                $paginator = Zend_Paginator::factory($this->messages->getModelOutbox($_SESSION['user']['id']));
                $paginator->setItemCountPerPage($nr);
                $paginator->setCurrentPageNumber($page);
                $this->view->paginator = $paginator;

                break;

            case "compose":

                $this->_data['page_title'] = 'compose message';

                $count = $this->messages->getCountUnreadInbox($_SESSION['user']['id'], $_SESSION['group']);
                $this->_data['unread_count'] = $count;

                if ($this->_request->isPost()) {

                    $post = $this->_request->getPost();
                    //p($post,1);
                    if ($post['send'] == 'Send Message' && $post['id_receiver'] >= 0 && $post['receiver_type'] && $post['subject'] && $post['message']) {
                        //$the_user = $this->user->getUserByName($post['sendtouser']);
                        //if(!$the_user->id) {
                        //    $this->_helper->FlashMessenger->addMessage(notice("There is no user with that name!"));
                        //   $this->_redirect($this->view->url(array("message_action"=>"compose"),"messages"));
                        // }

                        // this is only model to user !!!!
                        $post['id_sender'] = $_SESSION['user']['id'];
                        $post['sender_type'] = $_SESSION['group'];
                        // $post['id_receiver'] = $the_user->id;
                        // $post['receiver_type'] = 'user';
                        $post['send_date'] = time();

                        unset($post['sendtouser']);
                        unset($post['send']);


                        /* bad words filters */

                        $this->load("bad_words");
                        $badWords = $this->bad_words->getAllArray();
                        array_walk($post, 'badWords', $badWords);

                        /* end bad words filter */


                        $this->messages->insert($post);

                        $message = "Message sent.";

                        $this->load("notifications");
                        $this->notifications->addNotification(user()->id, "model", $_SESSION['user']['id'], 'user', "model-messages-send", $message, 1, getUserIp());

                        $this->_helper->FlashMessenger->addMessage(notice($message));

                        $addNotification = array(
                            "id_from" => $_SESSION['user']['id'],
                            "type_from" => $_SESSION['group'],
                            "id_to" => $post['id_receiver'],
                            "type_to" => $post['receiver_type'],
                            "type" => "new_message",
                            "notification" => "New message from " . $_SESSION['user']['screen_name'],
                            "ip" => $_SERVER["REMOTE_ADDR"],
                            "date" => time(),
                            "resource" => $this->messages->getAdapter()->lastInsertId()
                        );
                        $this->addNotification($addNotification, $post['id_receiver']);

                        $this->_redirect($this->view->url(array("message_action" => "outbox"), "messages-model"));
                    }

                    if ($post['reply'] == 'Reply') {
                        $this->_data["replyto"] = array("id" => (int)$post["userid"], "type" => $post["usertype"], "name" => $post["username"]);
                    }
                }

                break;

            default:
                $this->_redirect('/404/');
                break;
        }

    }

    /**
     *
     */
    public function newsletterAction()
    {

    }

    /**
     *
     */
    public function memberstandingsAction()
    {
        if (!$this->acl->isAllowed($_SESSION['group'], "member-standings", "view")) return $this->_forward("restrict");
    }

    /**
     *
     */
    public function statsAction()
    {

    }

    /**
     *
     */
    public function commentsreviewsAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], "comments", "view")) return $this->_forward("restrict");
        if ($this->request->isPost()) {
            if (!$this->acl->isAllowed($_SESSION['group'], "comments", "edit")) return $this->_forward("restrict");
        }

    }

    /**
     *
     */
    public function chatarchiveAction()
    {

    }

    /**
     *
     */
    public function requestfromadminAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], "request-from-admin", "view")) return $this->_forward("restrict");
        if ($this->request->isPost()) {
            if (!$this->acl->isAllowed($_SESSION['group'], "request-from-admin", "edit")) return $this->_forward("restrict");
        }

    }

    /**
     * @throws Zend_Exception
     */
    public function notesAction()
    {

        $this->load("model_notes");
        $notes = $this->model_notes->getNotesByModel($_SESSION['user']['id']);

        $this->_data['notes'] = $notes;

        if (isset($this->params["action_type"]) && $this->params["action_type"] == "delete" && isset($this->params["id_note"])) {
            $this->model_notes->deleteItem($this->params["id_note"], user()->id);
            $message = "Note deleted";
            $this->_helper->FlashMessenger->addMessage(notice($message));
            $this->_redirect($this->view->url(array(), "model-notes", true));
        }

        if ($this->_request->isPost()) {

            $post = $this->_request->getPost();

            if (!empty($this->_request->note)) { // Main profile
                if ($this->model_notes->saveItem(user()->id, $post["note"])) {
                    $message = "Notes successfully saved!";
                    $this->load("notifications");
                    $this->notifications->addNotification(user()->id, "model", user()->id, "model", "model-notes-edit", $message, 1, getUserIp());
                } else {
                    $message = "Saving failed";
                }
                $this->_helper->FlashMessenger->addMessage(notice($message));
            }

            $this->_redirect($this->view->url(array(), "model-notes", true));
        }


    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function usernotesAction()
    {

        $this->load("model_user_notes");

        $nr = 10;
        $post = $this->params;

        unset($post['controller']);
        unset($post['action']);
        unset($post['module']);

        $page = 1;
        if (isset($post['page'])) {
            $page = $post['page'];
            unset($post['page']);
        }

        $paginator = Zend_Paginator::factory($this->model_user_notes->getNotesByModel(user()->id));
        $paginator->setItemCountPerPage($nr);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

    }

    /**
     *
     */
    public function shopAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], "shop", "view")) return $this->_forward("restrict");
        if ($this->request->isPost()) {
            if (!$this->acl->isAllowed($_SESSION['group'], "shop", "edit")) return $this->_forward("restrict");
        }

    }

    /**
     *
     */
    public function bankAction()
    {

        if (!$this->acl->isAllowed($_SESSION['group'], "bank", "view")) return $this->_forward("restrict");
        if ($this->request->isPost()) {
            if (!$this->acl->isAllowed($_SESSION['group'], "bank", "edit")) return $this->_forward("restrict");
        }

    }

    /**
     * @param $allParams
     * @param $key
     * @return string
     */
    private function setResourceValue($allParams, $key)
    {

        $keyMap = array(
            'about_me' => 'about_me',
            'phone' => null,
            'nickname' => 'screen_name',
            'birthday' => 'birthday_real',
            'receive_newsletter' => null,
            'country' => null,
            'bio' => null,
            'turned_on' => null,
            'turned_off' => null,
            'private_do' => null,
            'interests_hobbies' => null,
            'room_rules' => null,
            'display_name' => null,
            'default_billing_address' => null,
            'default_shiping_address' => null,
            'billing_first_name' => 'first_name',
            'billing_last_name' => 'name',
            'chips' => "0",
            //'body_type' => 'body_type',
            'ethnicity' => null,
            'height' => 'height',
            'weight' => 'weight',
            'measurements' => null,
            'hair_color' => 'hair_color',
            'orientation' => 'orientation',
            'languages' => null,
            'zodiac_sign' => null,
            'eye_color' => null,
            'pubic_hair' => null,
            'timezone' => 'timezone',
            'gender' => 'gender',
        );

        $value = '';
        if (array_key_exists($key, $keyMap)) {

            if (!is_null($keyMap[$key])) {
                $value = $allParams[$keyMap[$key]];
            } else {
                $value = $keyMap[$key];
            }
        }

        return $value;
    }

    /**
     * @throws Zend_Exception
     */
    public function scheduleeventsAction()
    {

        $this->load('model_schedule');
        $this->_data['model'] = user();
        if ($this->request->isPost()) {
            $post = $this->_request->getPost();

            // Save a new event
            if ($this->_request->save == 'Save') {

                if (!$post['hour']) $post['hour'] = 00;
                if (!$post['minute']) $post['minute'] = 00;

                if (!$post['end_hour']) $post['end_hour'] = 00;
                if (!$post['end_minute']) $post['end_minute'] = 00;
                if ($post['type'] == 'other') $post['status'] = 1;

                $post['date'] = strtotime($post['date'] . ' ' . $post['hour'] . ':' . $post['minute']);
                $post['date'] = date('Y-m-d h:i:s' ,$post['date']);

                $post['to_date'] = strtotime($post['to_date'] . ' ' . $post['end_hour'] . ':' . $post['end_minute']);
                $post['to_date'] = date('Y-m-d h:i:s' ,$post['to_date']);

                if ($post['hour'] < 10) $post['hour'] = "0" . $post['hour'];
                if ($post['end_hour'] < 10) $post['end_hour'] = "0" . $post['end_hour'];

                $post['start_hour'] = $post['hour'] . "" . $post['minute'];
                $post['end_hour'] = $post['end_hour'] . "" . $post['end_minute'];

                if (!trim($post['title'])) {
                    $this->_helper->FlashMessenger->addMessage(notice("Title can not be empty!"));
                    $this->_redirect('model/scheduleEvents/');
                }

                $post['id_model'] = $_SESSION['user']['id'];

                /* status si id_show temporar dat static, pana la cfreare manage videos */

                $post["status"] = 1;
                // $post["reference_id"] = 1; // temporar dat static

                unset($post['id_item']);
                unset($post['save']);
                unset($post['hour']);
                unset($post['minute']);
                unset($post['end_minute']);
                unset($post['repeat_for']);
                unset($post['image']);

                $allDates = [];

                //@TODO check if all dates get selected as required
                if($post['day']) {
                    foreach ($post['day'] as $key => $day) {

                        $nextDate = strtotime('next  ' . ucfirst($day) . '', strtotime($post['date']));

                        while ($nextDate <= strtotime($post['to_date'])) {

                            $nextDate = strtotime('next ' . ucfirst($day) . '', $nextDate);
                            $allDates[] = date('Y-m-d-D h:i:s', $nextDate);
                        }
                    }
                }

                unset($post['day']);
                /* bad words filters */

                $this->load("bad_words");
                $badWords = $this->bad_words->getAllArray();
                array_walk($post, 'badWords', $badWords);

                /* end bad words filter */
                foreach ($allDates as $key=>$dates) {
                    $post['date'] = $dates;
                    $this->model_schedule->insert($post);
                    unset($post['date']);

                }

                $message = "Event successfully saved!";

                $this->load("notifications");
                $this->notifications->addNotification(user()->id, "model", user()->id, "model", "model-schedule-new-event", $message, 1, getUserIp());
                $this->_helper->FlashMessenger->addMessage(notice($message));

            }

            // Edit a new event
            if ($this->_request->save == 'Edit') {

                if ($post['id_item']) {
                    $event = $this->model_schedule->getScheduleEventById($post['id_item']);
                    if ($_SESSION['user']['id'] != $event->id_model) {
                        $this->_helper->FlashMessenger->addMessage(notice("You can not edit this event."));
                        $this->_redirect('model/scheduleEvents/');
                    }
                } else {
                    $this->_helper->FlashMessenger->addMessage(notice("Event was no found."));
                    $this->_redirect('model/scheduleEvents/');
                }

                if (!$post['hour']) $post['hour'] = '00';
                if (!$post['minute']) $post['minute'] = '00';

                if (!$post['end_hour']) $post['end_hour'] = '00';
                if (!$post['end_minute']) $post['end_minute'] = '00';
                if ($post['type'] == 'other') $post['status'] = 1;

                $post['id'] = $post['id_item'];
                $post['date'] = strtotime($post['to_date'] . ' ' . $post['hour'] . ':' . $post['minute']);
                $post['to_date'] = strtotime($post['to_date'] . ' ' . $post['end_hour'] . ':' . $post['end_minute']);

                if ($post['hour'] < 10) $post['hour'] = "0" . $post['hour'];
                if ($post['end_hour'] < 10) $post['end_hour'] = "0" . $post['end_hour'];

                $post['start_hour'] = $post['hour'] . "" . $post['minute'];
                $post['end_hour'] = $post['end_hour'] . "" . $post['end_minute'];

                if ($post['date'] < time() - 60 * 60 * 24) {
                    $this->_helper->FlashMessenger->addMessage(notice("You can not schedule an event in the past."));
                    $this->_redirect('model/scheduleEvents/');
                }

                if (!trim($post['title'])) {
                    $this->_helper->FlashMessenger->addMessage(notice("Title can not be empty!"));
                    $this->_redirect('model/scheduleEvents/');
                }

                unset($post['id_item']);
                unset($post['hour']);
                unset($post['minute']);
                unset($post['end_minute']);
                unset($post['save']);
                unset($post['image']);

                /* bad words filters */

                $this->load("bad_words");
                $badWords = $this->bad_words->getAllArray();
                array_walk($post, 'badWords', $badWords);

                /* end bad words filter */

                $this->model_schedule->update($post, $this->model_schedule->getAdapter()->quoteInto("id=?", $post['id']));

                $message = 'Event successfully saved!';

                $this->load("notifications");
                $this->notifications->addNotification(user()->id, "model", user()->id, "model", "model-schedule-edit-event", $message, 1, getUserIp());
                $this->_helper->FlashMessenger->addMessage(notice($message));
            }

            // Delete event
            if ($this->_request->save == 'Delete') {

                if ($post['id_item']) {
                    $event = $this->model_schedule->getScheduleEventById($post['id_item']);
                    if ($_SESSION['user']['id'] != $event->id_model) {
                        $this->_helper->FlashMessenger->addMessage(notice("You can not delete this event."));
                        $this->_redirect('model/scheduleEvents/');
                    }
                } else {
                    $this->_helper->FlashMessenger->addMessage(notice("Event was no found."));
                    $this->_redirect('model/scheduleEvents/');
                }

                $post['id'] = $post['id_item'];
                $this->model_schedule->delete($this->model_schedule->getAdapter()->quoteInto("id=?", $post['id']));

                $message = 'Event successfully deleted!';

                $this->load("notifications");
                $this->notifications->addNotification(user()->id, "model", user()->id, "model", "model-schedule-delete-event", $message, 1, getUserIp());
                $this->_helper->FlashMessenger->addMessage(notice($message));
            }

            $this->_redirect($this->view->url(array(), 'model-schedule-events'));
        }
    }

    public function badWordFilterAction()
    {
        $this->load("bad_words");

        $request = $this->_request;

        $post = $request->getPost();

        if ($post) {
            if (isset($post["save"])) {

                unset($post["save"]);
                $post['user_id'] = $_SESSION['user']['id'];
                $this->bad_words->insert($post);
                $this->_helper->FlashMessenger->addMessage(notice("Word saved!"));
                $this->_redirect("/admin/bad-words-filter");
            }
            if (isset($post["mark_delete"])) {
                $this->bad_words->deleteMultiple($post["multiple_select"]);
                $this->_helper->FlashMessenger->addMessage(notice("Words deleted!"));
                $this->_redirect("/admin/bad-words-filter");
            }
        }

        $this->_data["badWords"] = $this->bad_words->fetchAll($this->bad_words->select()->where('user_id=?', $_SESSION['user']['id']));

    }

    /**
     * @throws Zend_Exception
     */
    public function pageAction()
    {
        $this->load('static_pages');
        $this->load("video");

        $page = $this->_request->parent;

        $this->_data['page_content'] = $this->static_pages->getContent($page, 'backend');
        if (!$this->_data['page_content']) return $this->_forward("restrict");
        $this->_data['development'] = $this->static_pages->getPages('backend', $this->_data['page_content']->page);
        if (!count($this->_data['development'])) $this->_data['development'] = $this->static_pages->getPages('backend', $this->_data['page_content']->parent);
    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Paginator_Exception
     */
    public function notificationsAction()
    {
        $post = $this->params;
        $page = 1;
        if (isset($post['page'])) {
            $page = $post['page'];
            unset($post['page']);
        }

        $this->load("user_notifications");
        $post = $this->_request->getPost();
        if ($post) {
            if ($post["mark_read"] || $post["mark_unread"]) {
                if ($post["mark_read"]) $read = 1;
                else $read = 0;
                $this->user_notifications->markNotifications($post["multiple_select"], $read);
                $this->_redirect($this->view->url(array(), "model-notifications"));
            } elseif ($post["mark_delete"]) {
                $this->user_notifications->deleteNotifications($post["multiple_select"]);
            }
        }

        $notifications = $this->user_notifications->getAllType($_SESSION["group"], $_SESSION["user"]["id"], $this->acl->isAllowed($_SESSION['group'], "all_resources", "view"));

        foreach ($notifications as $n) {
            $last_notification = $n->id;
            break;
        }

        if ($last_notification > $_SESSION["user"]["last_notification"]) {
            //update session
            $_SESSION["user"]["last_notification"] = $last_notification;
            $this->load("model");
            $this->model->update(array("last_notification" => $last_notification), "id=" . $_SESSION["user"]["id"]);
        }

        $paginator = Zend_Paginator::factory($notifications);
        $paginator->setItemCountPerPage("25");
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

        //$this->_data["notifications"] = $notifications;

    }

    /**
     * @throws Zend_Exception
     */
    public function notificationsettingsAction()
    {
        $this->load("user_notifications_mail");
        $this->load("user_notifications_type");
        $post = $this->request->getPost();
        if ($post) {
            unset($post["settings"]["all"]);
            $this->user_notifications_mail->savePermissions($post["settings"], $_SESSION["group"], $_SESSION["user"]["id"]);
        }

        $this->_data["settings"] = $this->user_notifications_type->getAll();
        $userSettings = $this->user_notifications_mail->getPermissions($_SESSION["group"], $_SESSION["user"]["id"]);

        $settings = array();
        if ($userSettings) {
            foreach ($userSettings as $set) {
                $settings[] = $set->notification_type;
            }
        }
        $this->_data["userSettings"] = $settings;
    }

    /**
     * @throws Zend_Exception
     */
    public function verifyAction()
    {

        if ($this->request) {
            $code = $this->_request->getParam("code");
            if ($code) {
                $this->load("model");
                $response = $this->model->verifyEmail($code);
                $this->_helper->FlashMessenger->addMessage(notice($response["message"]));

                $this->load("templates");
                $tmpl = $this->templates->getContent("model_welcome_message");

                if ($response["status"] == "success") {
                    $tmpl->content = str_replace("{name}", $response["name"], $tmpl->content);

                    $message["id_sender"] = 0;
                    $message["sender_type"] = "moderator";
                    $message["id_receiver"] = $response["user"];
                    $message["receiver_type"] = "model";
                    $message["subject"] = $tmpl->title ? $tmpl->title : "Welcome";
                    $message["message"] = $tmpl->content ? $tmpl->content : "Welcome to our site";
                    $message["inbox"] = 1;
                    $message["outbox"] = 0;
                    $message["send_date"] = time();
                    $message["read"] = 1;
                    $message["tip"] = 0;
                    $message["type"] = "inbox";

                    $this->load("messages");
                    $this->messages->insert($message);

                    $addNotification = array(
                        "id_from" => 0,
                        "type_from" => "moderator",
                        "id_to" => $response["user"],
                        "type_to" => "model",
                        "type" => "new_message",
                        "notification" => $tmpl->title ? $tmpl->title : "Welcome",
                        "ip" => $_SERVER["REMOTE_ADDR"],
                        "read" => "0",
                        "date" => time(),
                        "resource" => $this->messages->getAdapter()->lastInsertId()
                    );
                    $this->addNotification($addNotification, $response["user"]);
                }

                $this->redirectToLogin('model', $this->getRequest()->getRequestUri());

            } else {
                $this->_redirect('/');
            }
        } else {
            $this->_redirect('/');
        }
        exit;
    }

    /**
     * @throws Zend_Exception
     */
    public function bannerManagementAction()
    {

        $this->load("banners");

        $this->_data['banner_zone'] = json_decode(config()->banner_zone);
        $request = $this->_request;
        $post = $request->getPost();

        if ($post) {
            if (isset($post["save"])) {
                unset($post["save"]);
                $post["start_date"] = strtotime($post["start_date"]);
                $post["end_date"] = strtotime($post["end_date"]);
                if (isset($this->_request->id_model))
                    $post["id_owner"] = $this->_request->id_model;

                elseif (Auth::isModel())
                    $post["id_owner"] = user()->id;


                $this->banners->insert($post);
                $addNotification = array(
                    "id_from" => $post["id_owner"],
                    "type_from" => "model",
                    "id_to" => 0,
                    "type_to" => "moderator",
                    "type" => "banner",
                    "notification" => "Model added new banner",
                    "ip" => $_SERVER["REMOTE_ADDR"],
                    "date" => time(),
                    "resource" => $post["id_owner"]
                );

                $this->addNotification($addNotification, "admin");

                $this->_helper->FlashMessenger->addMessage(notice("Banner saved, waiting for moderation"));

                if (isset($this->_request->id_model))
                    $this->_redirect($this->view->url(array("id_model" => $this->_request->id_model), "model-banners1"));
                elseif (Auth::isModel())
                    $this->_redirect($this->view->url(array(""), "model-banners"));

            }

            if (isset($post["delete_banner"])) {
                $this->banners->delete("id=" . (int)$post["id_banner"]);
            }
            /*
            if(isset($post["deny_banner"])){
                            $this->banners->update(array("status"=> 2),"id=".(int)$post["id_banner"]);
                        }
                        if(isset($post["approve_banner"])){
                            $this->banners->update(array("status"=> 1),"id=".(int)$post["id_banner"]);
                        }
            */
        }

        $this->_data["banners"] = $this->banners->getBannersModel(user()->id);

    }

    /**
     * @throws Zend_Exception
     */
    public function quotesAction()
    {

        $this->load("model_quotes");

        $request = $this->_request;
        $post = $request->getPost();
        if ($post) {
            if (isset($post["save"])) {

                unset($post["save"]);
                $post["id_model"] = user()->id;
                $post["text"] = strip_tags($post["text"]);

                /* bad words filters */
                $this->load("bad_words");
                $badWords = $this->bad_words->getAllArray();
                array_walk($post, 'badWords', $badWords);

                /* end bad words filter */

                $this->model_quotes->insert($post);
                $this->_helper->FlashMessenger->addMessage(notice("Quote saved!"));
                $this->_redirect("/admin/chat/quotes");
            }
            if (isset($post["mark_delete"])) {
                $this->model_quotes->deleteQuotes($post["multiple_select"]);
                $this->_helper->FlashMessenger->addMessage(notice("Quote(s) deleted!"));
                $this->_redirect("/admin/chat/quotes");
            }
        }
        $this->_data['quotes'] = $this->model_quotes->quoteSuggest();

    }

}