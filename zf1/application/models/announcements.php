<?

class announcements extends App_Model
{

    protected $_name = "announcements";

    protected $_primary = "id";

    /**
     * put your comment there...
     *
     * @param mixed $section --> all / models / mods
     * @param mixed $live --> 0 / 1
     * @param mixed $limit
     * @param mixed $start
     */
    public function getAnnouncements($section = null, $live = null, $start = null, $end = null, $sort = null)
    {

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("a" => "announcements"), "a.*")
            //->from(array("m" => "moderator"), "m.username")
            //->where("a.id_moderator=m.id")
            ->order("go_live desc");

        if ($section) {

            $select->where("a.active=1")->where("section='all' OR section=?", $section);
        }

        if ($start) $select->where("a.go_live>=?", $start);
        if ($end) $select->where("a.go_live<=?", $end);
        //if ($live) $select->where("a.go_live<=?", time());

        if ($sort) {
            if ($sort == "draft") $select->where("a.active=?", "-1");
            if ($sort == "approved") $select->where("a.active=?", "1");
            if ($sort == "pending") $select->where("a.active=?", "0");
            if ($sort == "active") {
                $select->where("a.go_live<=?", date('Y-m-d H:i:s'));
                $select->where("a.active=1");
            }
        }

        return $this->fetchAll($select);
    }

    public function getAnnouncementsLimit($section = null, $limit = null)
    {

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("a" => "announcements"), "a.*")
            ->from(array("m" => "moderator"), "m.username")
            ->where("go_live<=" . time())
            ->where("a.id_moderator=m.id")
            ->order("go_live desc");

        if ($section) {
            $select->where("a.active=1")->where("section='all' OR section=?", $section);
        }

        if ($limit) $select->limit($limit);

        return $this->fetchAll($select);
    }

    public function getAnnouncementsFrontend($section = null)
    {

        $str = "";
        if ($section) {
            foreach ($section as $type) {
                $str .= " AND section='" . $type . "'";
            }
        }
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("a" => "announcements"), "a.*")
            ->where("active = 1 AND go_live<=" . time())
            ->order("go_live desc")
            ->limit(5);

        if ($section) {
            $select->where("a.active=1")->where("section='all' OR section=?", $section);
        }

        if ($limit) $select->limit($limit);

        return $this->fetchAll($select);
    }

    public function getAnnouncementById($id)
    {

        return $this->fetchRow($this->select()->where("id=?", $id));

    }

}