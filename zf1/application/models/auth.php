<?php

use \Zend\Crypt\Password\Bcrypt;

class Auth extends Zend_Db_Table_Abstract
{

    const PROCESARE = 2;

    const GROUP_USER = "user";
    const GROUP_MODERATOR = "moderator";
    const GROUP_MODEL = "model";
    protected $_primary = array('id');

    public static function checkUser()
    {

        $sm = Zend_Registry::get('service_manager');

        if (
            current($sm->get('BjyAuthorize\Provider\Identity\ProviderInterface')->getIdentityRoles())->getRoleId() ==
            $sm->get('Config')['bjyauthorize']['default_role']
        ) {
            header("Location: /account/login");
            exit;
        }

    }

    public static function isModerator()
    {
/*        if ($_SESSION['group'] == self::GROUP_MODERATOR) return true;
        return false;*/
        $auth = Zend_Registry::get('service_manager')->get('zfcuser_auth_service');
        return $auth->getIdentity() && $auth->getIdentity()->hasModeratorRole();
    }

    public static function isModel()
    {
        /*if ($_SESSION['group'] == self::GROUP_MODEL) return true;
        return false;*/
        $auth = Zend_Registry::get('service_manager')->get('zfcuser_auth_service');
        return $auth->getIdentity() && $auth->getIdentity()->isPerformer();
    }

    public static function isUser()
    {
        /*return (self::isLogged() && !self::isModerator() && !self::isModel());*/
        $auth = Zend_Registry::get('service_manager')->get('zfcuser_auth_service');
        return $auth->getIdentity() && $auth->getIdentity()->getRole() == 'user';
    }

    public static function getUser()
    {
        /*return (self::isLogged() && !self::isModerator() && !self::isModel());*/
        return Zend_Registry::get('service_manager')->get('zfcuser_auth_service')->getIdentity();
    }

    public static function isLogged()
    {
        //return isLogged();
        return Zend_Registry::get('service_manager')->get('zfcuser_auth_service')->hasIdentity();
    }

    /**
     * User Login - frontend and backend
     *
     * @param mixed $email
     * @param mixed $password
     * @param mixed $group
     * @param mixed $url
     */
    public static function login($email, $password, $group = self::GROUP_USER, $url = "")
    {


        if (!in_array($group, array("moderator", "user", "model"))) return false;
        $pk = 'id';
        if (!Zend_Registry::isRegistered($group)) {
            Zend_Loader::loadFile(APPLICATION_PATH . "/models/" . $group . ".php", null, true);
            $table = new $group();
            Zend_Registry::set($group, $table);
        } else $table = Zend_Registry::get($group);

        $select = $table->select()->from(array("u" => $group));
        $bcrypt = new Bcrypt();

        $verify = true;

        //if($group == "model") $select->setIntegrityCheck(false)->joinLeft(array("mm" => "model_moderator"), "u.user_id = mm.id_model", array("id_moderator" => "id_moderator"));

        $select->where($table->getAdapter()->quoteInto("email=?", $email));

        $row = $table->_fetchRow($select);

        $securePass = $row->password;
        if ($group == 'user') $verify = $bcrypt->verify($password, $securePass);
        if ($verify) {


            if (!isset($row[$pk])) return false; // no user
            //if($group != 'model'){

            if ($row['state'] == 0) return "account pending"; //account pending
            //}
            if ($row['state'] == -1) return "account disabled"; //account disabled


            $sess['group'] = $group;
            $sess['user'] = $row->toArray();
            if(!isset($sess["id"]) && isset($sess["id"])) $sess["id"] = $sess["id"];
            if ($group == 'model') {

                $sess['user']['user_photo'] = $table->find($row[$pk])->current()->getCover();
                $sess["user"]["profile_url"] = "/performer/profile/" . $row[$pk] . "/" . $row["screen_name"];
                $sess['user'][$pk] = $row[$pk];

                if (!Zend_Registry::isRegistered("model_websites")) {
                    Zend_Loader::loadFile(APPLICATION_PATH . "/models/model_websites.php", null, true);
                    $webTable = new Model_websites();
                    Zend_Registry::set("model_websites", $webTable);
                } else $webTable = Zend_Registry::get("model_websites");

                $web = $webTable->fetchRow($webTable->select()->where("id_model=?", $row[$pk]));
                if ($web)
                    $sess['user']['website']["url"] = $web->url;
                else
                    $sess['user']['website']["url"] = null;

            } elseif ($group == 'user') {
                $sess['user']['user_photo'] = $table->find($row[$pk])->current()->getAvatar();
                $sess["user"]["profile_url"] = "/user/profile/" . $row[$pk] . "/" . $row["display_name"];
            }

            $sess['user']['id'] = $sess['user'][$pk];

            $sess['url'] = $url;
            $table->getAdapter()->query("update " . $group . " set last_login=NOW(), online=1, session_id='" . session_id() . "', last_activity=" . time() . "  WHERE " . $table->getAdapter()->quoteInto($pk . "=?", $sess['user']['id']));
            if (is_array($_SESSION)) $_SESSION = array_merge($_SESSION, $sess);
            else $_SESSION = $sess;
            return true;
        } else {

            return false;
        }

    }

    public static function loginAs($id = null, $group = "model")
    {

        if (is_null($id)) return false;

        if (!Zend_Registry::isRegistered($group)) {
            Zend_Loader::loadFile(APPLICATION_PATH . "/models/" . $group . ".php", null, true);
            $table = new $group();
            Zend_Registry::set($group, $table);
        } else $table = Zend_Registry::get($group);

        $select = $table->select()->from($table);

        $pk = 'id';

        $select->where($table->getAdapter()->quoteInto($pk . "=?", $id));

        $row = $table->_fetchRow($select);

        if (!isset($row[$pk])) return false; // no user


        $sess['group'] = $group;
        $sess['user'] = $row->toArray();

        if ($group == 'model') {
            $sess['user']['user_photo'] = $table->find($row[$pk])->current()->getCover();
            $sess["user"]["profile_url"] = "/performer/profile/" . $row[$pk] . "/" . $row["screen_name"];
        } elseif ($group == 'user') {
            $sess['user']['user_photo'] = $table->find($row[$pk])->current()->getAvatar();
            $sess["user"]["profile_url"] = "/user/profile/" . $row[$pk] . "/" . $row["display_name"];
        }

        $sess['url'] = '';
        $table->getAdapter()->query("update " . $group . " set last_login=NOW(), online=1, session_id='" . session_id() . "', last_activity=" . time() . "  WHERE " . $table->getAdapter()->quoteInto($pk . "=?", $row[$pk]));
        if (is_array($_SESSION)) $_SESSION = array_merge($_SESSION, $sess);
        else $_SESSION = $sess;

        return true;
    }


    public static function analyticsUserRole()
    {
        $role = 'Guest';

        if (self::isUser())
            $role = 'Member';
        elseif (self::isModel())
            $role = "Performer";
        elseif (self::isModerator())
            $role = 'Moderator';

        return $role;
    }

}
