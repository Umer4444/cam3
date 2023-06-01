<?php

use PerfectWeb\Core\Utils\Status;

class Model extends App_Model
{

    protected $_name = "user";

    protected $_primary = "id";

    protected $_rowClass = 'ModelRow';

    public function setStatus($id, $status, $type = 'room')
    {
        if ($type == 'room')
            $this->update(array("status" => $status), $this->getAdapter()->quoteInto("id=?", $id));
        else if ($type == 'profile')
            $this->update(array("status_profile" => $status), $this->getAdapter()->quoteInto("id=?", $id));

        return $this->fetchAll($this->select()->from(array("m" => $this->_name), array("count(*) as count", "status"))->where("m.id=?", $id));

    }

    /**
     * approve/deny models
     * @param $id
     * @param null $active
     * @return bool
     */
    public function setActive($id, $active = null)
    {

        if (!is_null($active)) {

            if ($active) $status = 1;
            else $status = -1;
            $this->update(array("active" => $status), $this->getAdapter()->quoteInto("id=?", $id));
            return true;

        } else {
            return false;
        }
    }

    /**
     * set the chat type
     * @param $id
     * @param $type
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function setChatType($id, $type)
    {

        $this->update(array("chat_type" => $type), $this->getAdapter()->quoteInto("id=?", $id));

        return $this->fetchAll($this->select()->from(array("m" => $this->_name), array("count(*) as count", "chat_type"))->where("m.id=?", $id));

    }

    /**
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function fetchAllModels()
    {

        $select = $this->select()->setIntegrityCheck(false)->from(array("m" => $this->_name), array('*'));
        $select->joinLeft(array("ur" => "user_resource"), "ur.resource='domain'", array("domain_resource_id" => "resource"));
        $select->joinLeft(array("urv" => "user_resource_value"), "urv.resource_id=ur.id AND urv.user_id = m.id", array("performer_domain" => "value"));

        return $this->fetchAll($select);
    }

    /**
     * @param int $active
     * @param null $search
     * @param null $live
     * @param null $filter
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getModels($active = 1, $search = null, $live = null, $filter = null)
    {
        // p($filter);
        $select = $this->select()->setIntegrityCheck(false)
            ->from(array("m" => $this->_name), array('*'))
            ->where("m.active=?", $active)
            ->joinLeft(array("u" => "webchat_users"), "u.id_model=m.id  AND u.id_user=concat('model_', m.id)", array('online' => "chat_type", "broadcast_mode" => "broadcast_mode", "quality" => "quality"));

        $select->joinLeft(array("ur" => "user_resource"), "ur.resource='domain'", array("domain_resource_id" => "resource"));
        $select->joinLeft(array("urv" => "user_resource_value"), "urv.resource_id=ur.id AND urv.user_id = m.id", array("performer_domain" => "value"));
        $select->joinLeft(array("ph" => "photos"), "ph.user = m.id and ph.type = 'id_photo' ", array("ph.filename"));
        $select->joinLeft(array("usr" => "user"), "usr.id = m.id", array("usr.referral_code", "usr.ip_address"));
        $select->joinLeft(array("us" => "user_studios"), "us.user_id = m.id", array("us.studios_id"));
        $select->joinLeft(array("st" => "studios"), "st.id = us.studios_id", array("st.name"));
        $select->joinLeft(array("ms" => "managers_studios"), "ms.studios_id = st.id", array("ms.manager_id"));
        $select->joinLeft(array("usrm" => "user"), "usrm.id = ms.manager_id", array("manager_username" => "usrm.username"));

        if (Auth::isLogged()) {
            $select->joinLeft(array("uf" => "user_favorites"), user()->id . "=uf.id_user AND uf.id_user=m.id", array("id_model_favorite" => "uf.id_user"));
            $select->joinLeft(array("f" => "followers"), user()->id . "=f.id_follower AND f.id_follower=m.id", array("id_model_followed" => "f.id_followed"));
        }
        if (isset($filter["categories"]) && !empty($filter["categories"])) {
            $select->joinRight(array("mcat" => "model_to_categories"), " FIND_IN_SET(mcat.id_category, '" . trim($filter["categories"], ",") . "') AND mcat.id_model=m.id", array(""));
        }

        if (isset($filter["hair_type"]) && !empty($filter["hair_type"])) {
            $select->where("FIND_IN_SET(m.hair_color, '" . trim($filter["hair_type"], ",") . "') ");
        }

        if (isset($filter["eye_color"]) && !empty($filter["eye_color"])) {
            $select->joinRight(array("mi" => "model_info"), "mi.id_model=m.id AND FIND_IN_SET(mi.value, '" . trim($filter["eye_color"], ",") . "')", array(""));
            $select->joinRight(array("in" => "info"), "mi.id_field=in.id", array(""));
        }

        if (isset($filter["languages"]) && !empty($filter["languages"])) {

            $sql = " AND ( ";

            $langs = array_filter(explode(",", strtolower($filter["languages"])));
            $count = count($langs);
            foreach ($langs as $k => $lang) {
                $sql .= "FIND_IN_SET( '" . trim($lang, ",") . "', lower(min.value))";
                if ($k + 1 < $count)
                    $sql .= " OR ";
            }
            $sql .= ")";

            $select->joinRight(array("min" => "model_info"), "min.id_model=m.id " . $sql, array(""));
            $select->joinRight(array("i" => "info"), "min.id_field=i.id", array(""));
        }

        if (isset($filter["orientation"]) && !empty($filter["orientation"])) {
            $select->where("FIND_IN_SET(m.orientation, '" . trim($filter["orientation"], ",") . "') ");
        }

        if (isset($filter["gender"]) && !empty($filter["gender"])) {
            $select->where("FIND_IN_SET(m.gender, '" . trim($filter["gender"], ",") . "') ");
        }

        if (isset($filter["age_id"]) && !empty($filter["age_id"]) && count($filter["age_id"]) == 2) {
            $select->where(new Zend_Db_Expr("TIMESTAMPDIFF(YEAR,m.birthday,NOW()) BETWEEN " . $filter["age_id"][0] . " AND " . $filter["age_id"][1]));
        }
        if (isset($filter["weight_id"]) && !empty($filter["weight_id"]) && count($filter["weight_id"]) == 2) {
            $select->where(new Zend_Db_Expr("m.weight BETWEEN " . $filter["weight_id"][0] . " AND " . $filter["weight_id"][1]));
        }
        //this will be for showing all models, except for hidden ones.
        if (Auth::isLogged()) {
            if (!isset($filter['hidden']) || !$filter['hidden']) {
                $select->where('NOT EXISTS (?)', new Zend_Db_Expr(
                    $this->getAdapter()->quoteInto('select * from hidden_models as hm where m.id = hm.model_id AND hm.user_id = ?', user()->id, Zend_Db::PARAM_INT)
                ));
            }
        }

        //SELECT TIMESTAMPDIFF(YEAR,model.birthday,NOW()) AS age from model

        //p($select."",1);
        if ($live) $select->where("u.chat_type is not null");

        //$select->order('u.chat_type DESC');
        $select->order("m.display_order ASC");

        $select->group("m.id");

        if (!is_null($search)) $select->where($this->getAdapter()->quoteInto('m.screen_name like ?', strtolower("%" . $search . "%")));
        // p($select."",1);
        return $this->fetchAll($select);
    }

    /**
     * @param int $active
     * @param null $search
     * @param null $live
     * @param null $filter
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getModelList($active = Status::ACTIVE, $search = null, $live = null, $filter = null)
    {

        $select = $this->select()->setIntegrityCheck(false)
            ->from(array("m" => $this->_name), array('*'))
            ->where("m.state=?", $active)
            ->joinLeft(array("u" => "webchat_users"), "u.id_model=m.id  AND u.id_user=concat('model_', m.id)", array('online' => "chat_type", "broadcast_mode" => "broadcast_mode", "quality" => "quality"));

        $select->joinLeft(array("ur" => "user_resource"), "ur.resource='domain'", array("domain_resource_id" => "resource"));
        $select->joinLeft(array("urv" => "user_resource_value"), "urv.resource_id=ur.id AND urv.user_id = m.id", array("performer_domain" => "value"));

        if (Auth::isLogged()) {
            $select->joinLeft(array("uf" => "user_favorites"), user()->id . "=uf.id_user AND uf.id_user=m.id", array("id_model_favorite" => "uf.id_user"));
            $select->joinLeft(array("f" => "followers"), user()->id . "=f.id_follower AND f.id_follower=m.id", array("id_model_followed" => "f.id_followed"));
        }

        if (isset($filter["categories"]) && !empty($filter["categories"])) {
            $select->joinRight(array("mcat" => "model_to_categories"), " FIND_IN_SET(mcat.id_category, '" . trim($filter["categories"], ",") . "') AND mcat.id_model=m.id", array(""));
        }

        if (isset($filter["hair_type"]) && !empty($filter["hair_type"])) {
            $select->where("FIND_IN_SET(m.hair_color, '" . trim($filter["hair_type"], ",") . "') ");
        }

        if (isset($filter["eye_color"]) && !empty($filter["eye_color"])) {
            $select->joinRight(array("mi" => "model_info"), "mi.id_model=m.id AND FIND_IN_SET(mi.value, '" . trim($filter["eye_color"], ",") . "')", array(""));
            $select->joinRight(array("in" => "info"), "mi.id_field=in.id", array(""));
        }

        if (isset($filter["languages"]) && !empty($filter["languages"])) {

            $sql = " AND ( ";

            $langs = array_filter(explode(",", strtolower($filter["languages"])));
            $count = count($langs);
            foreach ($langs as $k => $lang) {
                $sql .= "FIND_IN_SET( '" . trim($lang, ",") . "', lower(min.value))";
                if ($k + 1 < $count)
                    $sql .= " OR ";
            }
            $sql .= ")";

            $select->joinRight(array("min" => "model_info"), "min.id_model=m.id " . $sql, array(""));
            $select->joinRight(array("i" => "info"), "min.id_field=i.id", array(""));
        }

        if (isset($filter["orientation"]) && !empty($filter["orientation"])) {
            $select->where("FIND_IN_SET(m.orientation, '" . trim($filter["orientation"], ",") . "') ");
        }

        if (isset($filter["gender"]) && !empty($filter["gender"])) {
            $select->where("FIND_IN_SET(m.gender, '" . trim($filter["gender"], ",") . "') ");
        }

        if (isset($filter["age_id"]) && !empty($filter["age_id"]) && count($filter["age_id"]) == 2) {
            $select->where(new Zend_Db_Expr("TIMESTAMPDIFF(YEAR,m.birthday,NOW()) BETWEEN " . $filter["age_id"][0] . " AND " . $filter["age_id"][1]));
        }
        if (isset($filter["weight_id"]) && !empty($filter["weight_id"]) && count($filter["weight_id"]) == 2) {
            $select->where(new Zend_Db_Expr("m.weight BETWEEN " . $filter["weight_id"][0] . " AND " . $filter["weight_id"][1]));
        }
        //this will be for showing all models, except for hidden ones.
        if (Auth::isLogged()) {

            $select->where('NOT EXISTS (?)', new Zend_Db_Expr(
                $this->getAdapter()->quoteInto('select * from hidden_models where m.id = model_id AND user_id = ?', user()->id, Zend_Db::PARAM_INT)
            ));

        }

        //SELECT TIMESTAMPDIFF(YEAR,model.birthday,NOW()) AS age from model

        if ($live) $select->where("u.chat_type is not null");

        //$select->order('u.chat_type DESC');
        $select->order("m.display_order ASC");

        $select->group("m.id");

        if (!is_null($search)) $select->where($this->getAdapter()->quoteInto('m.screen_name like ?', strtolower("%" . $search . "%")));

        return $this->fetchAll($select);
    }

    /**
     * @return array
     */
    public function getMaxValues()
    {
        $select = $this->select()->setIntegrityCheck(false)
            ->from($this->_name, array(
                "max_age" => new Zend_Db_Expr("max(TIMESTAMPDIFF(YEAR,birthday,NOW()))"),
                "min_age" => new Zend_Db_Expr("min(TIMESTAMPDIFF(YEAR,birthday,NOW()))"),
//                "max_weight" => new Zend_Db_Expr("max(weight)"),
//                "min_weight" => new Zend_Db_Expr("min(weight)"),
//                "max_height" => new Zend_Db_Expr("max(height)"),
//                "min_height" => new Zend_Db_Expr("min(height)"),
            ));
        //  p($select."","1");
        return $this->fetchRow($select)->toArray();
    }

    /**
     * get a model by active/id
     * @param $id
     * @param bool $active
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function getModel($id, $active = false)
    {
        $select = $this->select()->setIntegrityCheck(false)
            ->from(array("m" => $this->_name), array('*'));
        /*if ($active)
            $select->where("m.active=?", $active);*/

        $select->where("m.id=?", (int)$id)
            ->joinLeft(array("u" => "webchat_users"), "u.id_model=m.id  AND u.id_user=concat('model_', m.id)", array('online' => "chat_type"));
        if (Auth::isLogged()) {
            $select->joinLeft(array("uf" => "user_favorites"), user()->id . "=uf.id_user AND uf.id_user=m.id", array("id_model_favorite" => "uf.id_user"));
            $select->joinLeft(array("f" => "followers"), user()->id . "=f.id_follower AND f.id_follower=m.id", array("id_model_followed" => "f.id_followed"));
        } //}elseif(Auth::isLogged() && Auth::isModerator()){
        $select->joinLeft(array("mm" => "model_moderator"), "mm.id_model=m.id", array("assigned_to" => New Zend_Db_Expr("COALESCE(mm.id_moderator, 0)")));
        //  $select->joinLeft(array("w" => "model_websites"), "m.id=w.id_model", array("title" => "title", "url" => "url"));

        $select->joinLeft(array("v" => "video"), "m.id=v.id AND v.type='bio'", array("bio_video" => new Zend_Db_Expr(("coalesce(filename,'" . config()->video_default_bio . "')"))));
        $select->limit(1);

        //}
        //coalesce(sum(s.unit_sales)

        // address
        /*$select->joinLeft(array("co" => "countries"), "m.country=co.id", array("country_name" => "co.name"));
        $select->joinLeft(array("re" => "countries"), "m.region=re.id", array("region_name" => "re.name"));
        $select->joinLeft(array("ci" => "countries"), "m.city=ci.id", array("city_name" => "ci.name"));

        // gift address
        $select->joinLeft(array("gco" => "countries"), "m.country=gco.id", array("gift_country_name" => New Zend_Db_Expr("COALESCE(gco.name, null)")));
        $select->joinLeft(array("gre" => "countries"), "m.region=gre.id", array("gift_region_name" => New Zend_Db_Expr("COALESCE(gre.name,null)")));
        $select->joinLeft(array("gci" => "countries"), "m.city=gci.id", array("gift_city_name" => New Zend_Db_Expr("COALESCE(gci.name, null)")));

        // gift office address
        if (config()->gift_country) {
            $select->joinLeft(array("goco" => "countries"), config()->gift_country . "=goco.id", array("gift_office_country_name" => "goco.name"));
        }
        if (config()->gift_region) {
            $select->joinLeft(array("gore" => "countries"), config()->gift_region . "=gore.id", array("gift_office_region_name" => "gore.name"));
        }
        if (config()->gift_city) {
            $select->joinLeft(array("goci" => "countries"), config()->gift_city . "=goci.id", array("gift_office_city_name" => "goci.name"));
        }*/

        return $this->fetchRow($select);
    }

    /**
     * get one by id
     * @param $id_model
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function getById($id_model)
    {
        return $this->getModelById($id_model);
    }

    /**
     * get one model by id
     * @param null $id_model
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function getModelById($id_model = null)
    {
        $select = $this->select()->setIntegrityCheck(false)
            ->from(array("m" => $this->_name), array('*'));

        $select->where("m.id=?", (int)($id_model ? $id_model : $_SESSION['user']['id']))
            ->joinLeft(array("u" => "webchat_users"), "u.id_model=m.id  AND u.id=concat('model_', m.id)", array('online' => "chat_type"));

        if (Auth::isLogged()) {
            $select->joinLeft(array("uf" => "user_favorites"), user()->id . "=uf.id_user AND uf.id_user=m.id", array("id_model_favorite" => "uf.id_user"));
            $select->joinLeft(array("f" => "followers"), user()->id . "=f.id_follower AND f.id_follower=m.id", array("id_model_followed" => "f.id_followed"));
        } //}elseif(Auth::isLogged() && Auth::isModerator()){

        $select->joinLeft(array("mm" => "model_moderator"), "mm.id_model=m.id", array("assigned_to" => New Zend_Db_Expr("COALESCE(mm.id_moderator, 0)")));
        //  $select->joinLeft(array("w" => "model_websites"), "m.id=w.id_model", array("title" => "title", "url" => "url"));
        // address
        /*$select->joinLeft(array("co" => "countries"), "m.country=co.id", array("country_name" => "co.name"));
        $select->joinLeft(array("re" => "countries"), "m.region=re.id", array("region_name" => "re.name"));
        $select->joinLeft(array("ci" => "countries"), "m.city=ci.id", array("city_name" => "ci.name"));

        // gift address
        $select->joinLeft(array("gco" => "countries"), "m.country=gco.id", array("gift_country_name" => New Zend_Db_Expr("COALESCE(gco.name, null)")));
        $select->joinLeft(array("gre" => "countries"), "m.region=gre.id", array("gift_region_name" => New Zend_Db_Expr("COALESCE(gre.name,null)")));
        $select->joinLeft(array("gci" => "countries"), "m.city=gci.id", array("gift_city_name" => New Zend_Db_Expr("COALESCE(gci.name, null)")));

        // gift office address
        if (config()->gift_country) {
            $select->joinLeft(array("goco" => "countries"), config()->gift_country . "=goco.id", array("gift_office_country_name" => "goco.name"));
        }
        if (config()->gift_region) {
            $select->joinLeft(array("gore" => "countries"), config()->gift_region . "=gore.id", array("gift_office_region_name" => "gore.name"));
        }
        if (config()->gift_city) {
            $select->joinLeft(array("goci" => "countries"), config()->gift_city . "=goci.id", array("gift_office_city_name" => "goci.name"));
        }*/


        $select->joinLeft(array("v" => "video"), "m.id=v.id AND v.type='bio'", array("bio_video" => new Zend_Db_Expr(("coalesce(filename,'" . config()->video_default_bio . "')"))));
        $select->limit(1);

        //}
        //coalesce(sum(s.unit_sales)
;
        return $this->fetchRow($select);

        //return $this->fetchRow($this->select()->where("id=?", $id_model?$id_model:$_SESSION['user']['id']));
    }

    /**
     * get model by screen name
     * @param $screen_name
     * @return null|Zend_Db_Table_Row_Abstract
     *
     */
    public function getModelByScreen_name($screen_name)
    {
        return $this->fetchRow($this->select()->where("screen_name=?", $screen_name)->where("active=1"));
    }

    /**
     * search models by name
     * @param $screen_name
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function searchModelsByName($screen_name)
    {
        $result = $this->fetchAll($this->select()->where("screen_name LIKE ?", "%" . $screen_name . "%")->where("active=1"));

        return $result;
    }

    /**
     *
     * get status of a model
     * @param null $id
     * @return array
     *
     */
    function getStatus($id = null)
    {

        if (!$id) return array("status" => "");
        $status = $this->fetchRow($this->select()->where("id=?", $id))->status;

        return array("status" => $status);

    }

    /**
     * check if model screen name exists
     * @param $val
     * @param null $id_model
     * @return null|Zend_Db_Table_Row_Abstract
     */
    function checkScreenName($val, $id_model = null)
    {
        $check = $this->select()->where("screen_name=?", $val);
        if (Auth::isModel() || $id_model) $check->where("id<>?", Auth::isModel() ? $_SESSION['user']['id'] : $id_model);
        return $this->fetchRow($check);
    }

    /**
     * check if email is unique
     * @param $val
     * @param null $id_model
     * @return null|Zend_Db_Table_Row_Abstract
     */
    function checkUniqueEmail($val, $id_model = null)
    {
        $check = ($this->select()->where("email=?", $val));
        if (Auth::isModel() || $id_model) $check->where("id<>?", Auth::isModel() ? $_SESSION['user']['id'] : $id_model);
        return $this->fetchRow($check);
    }

    /**
     * get user favorites
     * @param $user_id
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getFavorite($user_id)
    {

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("f" => "user_favorites"))
            ->from(array("m" => "user"), "m.*")
            ->where("m.active=1")
            ->where("f.id_user=?", $user_id)
            ->where("f.id_user=m.id");

        return $this->fetchAll($select);

    }

    /**
     * get follower for user
     * @param $user_id
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getfollowerByUser($user_id)
    {

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("f" => "followers"))
            ->from(array("m" => "user"), "m.*")
            ->where("m.active=1")
            ->where("f.id_follower = ?", $user_id)
            ->where("f.id_follower = m.id");

        return $this->fetchAll($select);

    }

    /**
     * change online status to 0
     * @param null $id
     * @return bool
     */
    public function logout($id = null)
    {
        $this->update(array('online' => 0, 'session_id' => '-'), db()->quoteInto('id=?', $id));
        return true;
    }

    /**
     * get email notification
     * @param null $id
     * @return string
     */
    public function getNotificationEmail($id = null)
    {

        if (is_null($id)) return false;

        $row = $this->fetchRow($this->select(array("email", "notification_email"))->where("id=" . (int)$id));
        if ($row->notification_email)
            $email = $row->notification_email;
        else
            $email = $row->email;

        return $email;
    }

    /**
     * get name array
     * @return array
     */
    public function nameArray()
    {
        $field = "screen_name";
        $results = $this->fetchAll($this->select()->from($this->_name, array("id", $field)));
        $users = array();
        foreach ($results as $r) {
            $users[$r->id] = $r->$field;
        }

        return $users;
    }

    /**
     * get model details in array
     * @return array
     */
    public function modeldetailsArray()
    {
        $results = $this->fetchAll($this->select()->from($this->_name));
        $modeldetails = array();
        foreach ($results as $r) {
            $modeldetails[$r->id] = array($r->first_name . " " . $r->name, $r->chips, $r->rating);
        }

        return $modeldetails;
    }


    /*    public function verifyEmail($code=null){

            if(!$code) return "No code provided";

            $u = $this->select()->where("activation_code =?" , $code)->limit(1);
            if($u) {
                if($this->update(array("active" => 0, "activation_code" => null),  $this->getAdapter()->quoteInto("activation_code =?", $code)))
                    return "Email verified. A moderator will review your profile. You will receive an email then you can login";
                else
                    return "Error while activation. Try again later";
            } else {
                return "Invalid code";
            }
        }
        */
    /**
     * verify email
     * @param null $code
     * @return array
     */
    public function verifyEmail($code = null)
    {

        if (!$code) return "No code provided";

        $u = $this->fetchRow($this->select()->from($this->_name, array("id", "activation_code", "screen_name"))->where("activation_code =?", $code)->limit(1));
        if ($u) {
            if ($this->update(array("active" => 1, "activation_code" => null), $this->getAdapter()->quoteInto("activation_code =?", $code)))
                return array("message" => "Account activated. You can login", "user" => $u->id, "name" => $u->screen_name, "status" => "success");
            else
                return array("message" => "Error while activation. Try again later", "status" => "fail");
        } else {
            return array("message" => "Invalid code", "status" => "fail");
        }
    }

    /**
     * suggest username
     * @param null $query
     * @return array
     */
    public function usernameSuggest($query = null)
    {
        if (!$query)
            return false;

        $select = $this->select()
            ->from(array("m" => $this->_name), array("id", "screen_name"))
            ->where("screen_name LIKE ?", $query . '%')
            ->limit(5);

        if ($_SESSION["group"] == "model") {
            $select->joinRight(array("me" => "messages"), "(m.id=me.id_sender AND me.sender_type = 'moderator') OR (m.id=me.id_receiver AND me.receiver_type = 'moderator')", array(""));
        }

        $result = $this->fetchAll($select);

        $arr = array();
        foreach ($result as $row) {
            $arr[] = array('id' => $row->id, 'name' => utf8_encode($row->screen_name), "type" => "model");
        }
        return ($arr);
    }

    /**
     *
     * get names
     * @return array
     */
    public function getNames()
    {

        $select = $this->select()->setIntegrityCheck(false)
            ->from(array("m" => $this->_name), array("id", "screen_name"))
            ->joinRight(array("p" => "photos"), "(m.id=p.user AND p.type = 'cover')", array("filename" => "filename"))
            ->where("m.active = 1");


        $result = $this->fetchAll($select);
        $default = "model_default_cover";
        $arr = array();
        foreach ($result as $row) {

            if ($row->filename) {
                $file_ = $row->filename;
            } else {
                $file_ = config()->{$default};
            }
            $arr[$row->id] = array(
                'name' => $row->screen_name,
                "picture" => $file_
            );
        }

        return ($arr);
    }

    /**
     * navigate model
     * @param $request
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function navigateModel($request)
    {

        $sign = "=";

        if ($request->nav == "next")
            $sign = ">";
        if ($request->nav == "previous")
            $sign = "<";

        $select = $this->select()->setIntegrityCheck(false)
            ->from(array("m" => $this->_name), array("id", "screen_name", "display_order"))
            ->joinLeft(array("u" => "webchat_users"), "u.id_model=m.id  AND u.id_user=concat('model_', m.id)", array(""))
            ->where("m.display_order" . $sign . "?", (int)$request->position)
            ->where("u.chat_type is not null")
            ->where("m.active=1")->limit(1);

        return $this->fetchRow($select);
    }

}

/**
 * model row methods (repository)
 * Class ModelRow
 */
class ModelRow extends Zend_Db_Table_Row
{

    /**
     * set auto approve
     * @param null $active
     * @return bool
     */
    public function setAutoApprove($active = null)
    {

        if (!isset($active)) return false;


        $where = "id=" . $this->id;


        db()->update("user", array("auto_approve" => $active), $where);

        return true;


    }

    /**
     * change guestbook settings
     * @param null $status
     * @return bool
     */
    public function setGuestbookStatus($status = null)
    {

        if (!isset($status)) return false;


        $where = "id=" . $this->id;


        db()->update("user", array("guestbook" => $status), $where);

        return true;


    }

    /**
     * get model cover
     *
     * @param null $all_data
     * @param string $type
     * @param string $dir
     * @param string $default
     * @return null|string|Zend_Db_Table_Row_Abstract
     *
     */
    function getCover($all_data = null, $type = \Images\Entity\Photo::COVER, $dir = "photos", $default =
    "model_default_cover")
    {
        $model = new Model;
        $select = $model->select()
            ->setIntegrityCheck(false)
            ->from(array("p" => "photos"), "*")
            ->where("p.user=?", $this->id)
            ->where("p.type =?", $type)
            ->where("p.status =?", 1)
            ->order("p.id DESC");

        $result = $model->fetchRow($select);

        if ($result->filename) {
            if ($all_data) {
                return $result;
            } else {
                if (file_exists(APPLICATION_PATH . "/../../public" . $result->filename)) {
                    return $result->filename;
                } else {
                    return config()->{$default};
                }
            }
        } else {
            return config()->{$default};
        }
    }

    /**
     *
     * get headshot picture
     * @param null $all_data
     * @return null|string|Zend_Db_Table_Row_Abstract
     */
    function getHeadshot($all_data = null)
    {
        return $this->getCover($all_data, 'headshot', "users/".$this->id.'/profile', "model_default_headshot");
    }

    /**
     * get photo Id
     * @param null $all_data
     * @return null|string|Zend_Db_Table_Row_Abstract
     *
     */
    function getPhotoId($all_data = null)
    {
        return $this->getCover($all_data, 'photo_id', "users/".$this->id.'/profile', "model_default_photo_id");
    }

    /**
     * @param null $all_data
     * @return null|string|Zend_Db_Table_Row_Abstract
     */
    function get2257Form($all_data = null)
    {
        return $this->getCover($all_data, '2257_form', "accounts", "model_default_2257_form");
    }

    /**
     * @param null $all_data
     * @return null|string|Zend_Db_Table_Row_Abstract
     */
    function getW9Form($all_data = null)
    {
        return $this->getCover($all_data, 'w9_form', "accounts", "model_default_w9_form");
    }

    /**
     * @param null $all_data
     * @return null|string|Zend_Db_Table_Row_Abstract
     */
    function getReleaseForm($all_data = null)
    {
        return $this->getCover($all_data, 'release_form', "accounts", "model_default_release_form");
    }

    /**
     * pending documents
     *
     */
    function getAllCovers($type = "cover", $dir = "photos", $default = "model_default_cover", $status = 'pending')
    {

        $model = new Model;
        $results = $model->select()
            ->setIntegrityCheck(false)
            ->from(array("p" => "photos"), "*")
            ->where("p.user=?", $this->id);

        if ($status == 'pending')
            $results->where("p.status=?", 2);
        if ($status == 'rejected')
            $results->where("p.status=?", 0);

        $results->where("p.type =?", $type)
            ->order("p.id DESC");

        $resultSet = $model->fetchAll($results);
        $response = array();
        foreach ($resultSet as $r) {
            $response[$r->id]["status"] = $r->status;
            $response[$r->id]["file"] = "/uploads/" . $dir . "/" . $r->filename;
        }

        return $response;

    }

    /**
     * @param string $status
     * @return array
     */
    function getAllPhotoCover($status = "pending")
    {
        return $this->getAllCovers("cover", "photos", "model_default_cover", $status);
    }

    /**
     * @param string $status
     * @return array
     */
    function getAllPhotoIds($status = "pending")
    {
        return $this->getAllCovers('photo_id', "accounts", "model_default_photo_id", $status);
    }

    /**
     * @param string $status
     * @return array
     */
    function getAll2257Form($status = "pending")
    {
        return $this->getAllCovers('2257_form', "accounts", "model_default_2257_form", $status);
    }

    /**
     * @param string $status
     * @return array
     */
    function getAllW9Form($status = "pending")
    {
        return $this->getAllCovers('w9_form', "accounts", "model_default_w9_form", $status);
    }

    /**
     * @param string $status
     * @return array
     */
    function getAllReleaseForm($status = "pending")
    {
        return $this->getAllCovers('release_form', "accounts", "model_default_release_form", $status);
    }

    /**
     * @param null $type
     * @param null $not_id
     * @param string $status
     * @return bool
     * activate document!
     */

    function activateDocument($type = null, $not_id = null, $status = "rejected")
    {

        if (!$type || !$not_id || !$status) return false;

        if ($status == 'pending') $active = 2;
        elseif ($status == 'rejected') $active = 0;
        else $active = 1;

        $where = "active != 1 AND id_model=" . $this->id . " AND type='" . $type . "'";
        if ($not_id !== "all") {
            $where .= " AND id !=" . $not_id;

            db()->update("photos", array("status" => '1'), "id =" . $not_id);
        }

        db()->update("photos", array("status" => $active), $where);
        //db()->delete("photos", " id_model=".$this->id." AND type='".$type."' AND id !=".$not_id);
        return true;
    }

    /**
     * @param null $type
     * @param null $not_id
     * @return bool
     */
    function deleteDocuments($type = null, $not_id = null)
    {
        if (!$type) return false;
        $where = " status = 0 AND  user=" . $this->id . " AND type='" . $type . "'";
        if ($not_id != "all")
            $where .= " AND id =" . $not_id;

        db()->delete("photos", $where);
        return true;
    }

    /**
     * @param null $id_user
     * @param int $id_cam
     * @return string
     */
    function getStream($id_user = null, $id_cam = 1)
    {

        $md5 = md5($this->id . $id_cam);
        if (is_null($id_user)) return md5(substr($md5, 12) . substr($md5, 0, 10));
        else return md5(substr($md5, 12) . substr($md5, 0, 10) . md5($id_user));
    }

    /**
     * @return array
     */
    function getModelStatus()
    {
        $model = new Model;
        $result = $model->fetchRow($model->select()
                ->setIntegrityCheck(false)
                ->from(array("u" => "webchat_users"), "*")
                ->where("u.id_model=?", $this->id)
                ->where("u.id_user=?", "model_" . $this->id)
        );

        if (!$result->chat_type) return array('id_status' => 0, 'status' => 'Currently Offline', 'last_activity' => $result->last_activity);
        if ($result->chat_type == 'normal') return array('id_status' => 1, 'status' => 'Live Now', 'last_activity' => $result->last_activity);

        return array('id_status' => 2, 'status' => ucwords(($result->chat_type == "show" ? "Live" : $result->chat_type)) . ' Show', 'last_activity' => $result->last_activity);
    }

    /**
     * @return array|null|Zend_Db_Table_Row_Abstract
     */
    function getModelCountry()
    {
        $model = new Model;
        if ($this->country) {
            $result = $model->fetchRow($model->select()
                    ->setIntegrityCheck(false)
                    ->from(array("c" => "countries"), "*")
                    ->where("c.id=?", $this->country)
            );
            return $result;
        } else return array('id' => 0, "code" => "--", 'name' => 'None');
    }

    /**
     * @return null|Zend_Db_Table_Row_Abstract
     */
    function isfollower()
    {

        if (!Auth::isUser()) return false;

        $model = new Model;
        $result = $model->fetchRow($model->select()
                ->setIntegrityCheck(false)
                ->from(array("f" => "followers"), "*")
                ->where("id_follower=?", $this->id)
                ->where("id_user=?", $_SESSION['user']['id'])
        );
        return $result;

    }

    /**
     * @param null $only_main
     * @return null|Zend_Db_Table_Row_Abstract|Zend_Db_Table_Rowset_Abstract
     */
    function getModelCategories($only_main = null)
    {
        $model = new Model;
        $select = $model->select()
            ->setIntegrityCheck(false)
            ->from(array("c" => "categories"), "c.name")
            ->from(array("link" => "model_to_categories"), "*")
            ->where("link.id_model=?", $this->id)
            ->where("link.id_category = c.id")
            ->order("sort asc");

        if ($only_main) {
            return $model->fetchRow($select);
        } else {
            return $model->fetchAll($select);
        }
    }

    /**
     * @return array
     */
    function getModelRates()
    {

        $select = db()->query("select m.value,
                              (select l.value from rates_limits as l where l.id_rate=r.id and l.limit_type=\"min\") as min ,
                              (select l1.value from rates_limits as l1 where l1.id_rate=r.id and l1.limit_type=\"max\") as max,
                              r.type,
                              r.id
                       from rates as r
                       left join model_rates as m on (r.id=m.id_rate and m.id_model=" . $this->id . ") ");

        $_result = $select->fetchAll();

        $result = array();
        foreach ($_result as $row) {
            $result[$row['type']] = $row['value'];
        }
        return $result;
    }

}

