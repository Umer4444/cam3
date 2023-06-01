<?

class Followers extends App_Model
{

    protected $_name = "followers";

    protected $_primary = "id";


    public function isFollowing($id_model, $id_user = null)
    {
        $id_user = $id_user ? $id_user : $_SESSION['user']['id'];
        $result = $this->fetchRow($this->select()->where("id_follower=?", $id_model)->where("id=?", $id_user));
        return $result;
    }

    public function addFollow($id_model, $id_user = null, $type = null)
    {

        $id_user = $id_user ? $id_user : $_SESSION['user']['id'];

        if (!$id_model || !$id_user) return 0;

        $options = array(
            "video" => array("field" => "new_video", "value" => ($type ? ($type == "video" ? 1 : 0) : 1)),
            "photo" => array("field" => "new_photo", "value" => ($type ? ($type == "photo" ? 1 : 0) : 1)),
            "online" => array("field" => "when_online", "value" => ($type ? ($type == "online" ? 1 : 0) : 1)),
            "blog" => array("field" => "blog", "value" => ($type ? ($type == "blog" ? 1 : 0) : 1)),
            "pledge" => array("field" => "pledge", "value" => ($type ? ($type == "pledge" ? 1 : 0) : 1)),
        );

        $data = null;
        if ($type)
            $data = array($options[$type]["field"] => $options[$type]["value"]);

        if ($data) $update = $this->update($data, $this->getAdapter()->quoteInto("id_follower=?", $id_model) . " AND
        " . $this->getAdapter()->quoteInto("id_user=?", $id_user));

        if (!$update || !$type) {

            $this->getAdapter()->query("delete from " . $this->_name . " where " . $this->getAdapter()->quoteInto
                                       ("id_follower=?", $id_model) . "
                                                                    and " . $this->getAdapter()->quoteInto("id_user=?", $id_user));
            $sql = "insert into " . $this->_name . " set ";
            $sql .= $this->getAdapter()->quoteInto("id_follower=?", $id_model) . " ,";
            $sql .= $this->getAdapter()->quoteInto("id_user=?", $id_user) . " ,";

            foreach ($options as $option) {
                $sql .= $this->getAdapter()->quoteInto($option["field"] . "=?", $option["value"]) . ", ";
            }

            $sql .= $this->getAdapter()->quoteInto("added=?", time());

            $this->getAdapter()->query($sql);

        }


        return $this->getAdapter()->query("select count(*) as count from " . $this->_name . "
                                                       where " . $this->getAdapter()->quoteInto("id_follower=?",
                                                                                                $id_model) . "
                                                       and " . $this->getAdapter()->quoteInto("id_user=?", $id_user)
        )->fetchAll();
    }

    public function removeFollow($id_model, $id_user = null, $type = null)
    {
        $id_user = $id_user ? $id_user : $_SESSION['user']['id'];

        if ($type == "video")
            $this->update(array("new_video" => 0), $this->getAdapter()->quoteInto("id_follower=?", $id_model) . " AND
            " . $this->getAdapter()->quoteInto("id_user=?", $id_user));
        elseif ($type == "photo")
            $this->update(array("new_photo" => 0), $this->getAdapter()->quoteInto("id_follower=?", $id_model) . " AND
            " . $this->getAdapter()->quoteInto("id_user=?", $id_user));
        elseif ($type == "online")
            $this->update(array("when_online" => 0), $this->getAdapter()->quoteInto("id_follower=?", $id_model) . " AND
            " . $this->getAdapter()->quoteInto("id_user=?", $id_user));
        elseif (!$type)
            $this->getAdapter()->query("delete from " . $this->_name . " where " . $this->getAdapter()->quoteInto
                                       ("id_follower=?", $id_model) . "
                                                                    and " . $this->getAdapter()->quoteInto("id_user=?", $id_user));

        return true;
    }

    public function getFollowers()
    {
        $query = $this->select()->setIntegrityCheck(false)->from($this->_name)->joinLeft(array("model_actions"),
                                                                                         "followers.id_follower =
                                                                                         model_actions.id_model", array())
            ->joinLeft(array("user"), "followers.id_follower = user.id", array("screen_name" => "screen_name"))
            ->group("followers.id");

        $followers = $this->fetchAll($query);

        $ff = array();
        foreach ($followers as $follow) {
            $ff[] = array(
                "model" => $follow->screen_name,
                "when_online" => $follow->when_online,
                "pledge" => $follow->pledge,
                "blog" => $follow->blog,
                "new_video" => $follow->new_video,
                "new_photo" => $follow->new_photo,
                "id_follower" => $follow->id_model,
                "id_user" => $follow->id_user,
            );
        }

        return $ff;
    }


}