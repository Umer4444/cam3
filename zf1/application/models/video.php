<?php

use PerfectWeb\Core\Utils\Status;

class Video extends App_Model
{

    protected $_name = "video";

    protected $_rowClass = 'VideoRow';

    function getVideo($id_video, $active = true, $id_model = false)
    {
        if (!$id_video) return false;

        $select = $this->select()->setIntegrityCheck(false)
            ->from(array("v" => "video"), "*")
            ->from(array("m" => "user"), array("screen_name"))
            ->joinLeft(["c" => 'photos'], 'v.cover=c.id and c.entity=\'Videos\\\Entity\\\VideoCoverImage\'',
                ['filename as cover'])
            ->where("v.user_id=m.id");
        if ($active)
            $select->where("v.status= ?", Status::ACTIVE);
        if ($id_model)
            $select->where("v.id=?", (int)$id_model);

        $select->where("v.id=?", $id_video);

        $select->joinLeft(array("mm" => "model_moderator"), "mm.id_model=v.id", array("assigned_to" => "id_moderator"));

        $select->joinLeft(array("r" => "reviews"),
            "v.id=r.resource_id AND r.resource_type='video' AND r.active=1",
            array("total_active_reviews" => new Zend_Db_Expr("count(r.id)")
            ));
        return $this->fetchRow($select);
    }

    function getVideoById($id_video = null)
    {

        if (!$id_video) {
            return false;
        }
        $select = $this->select()->setIntegrityCheck(false)
            ->from(array("v" => "video"), "*")
            ->joinLeft(["c" => 'photos'], 'v.cover=c.id and c.entity=\'Videos\\\Entity\\\VideoCoverImage\'',
                ['filename as cover'])
            ->group("v.id");
        $select->joinLeft(array("r" => "reviews"),
            "v.id=r.resource_id AND r.resource_type='video' AND r.active=1",
            array("total_active_reviews" => new Zend_Db_Expr("count(r.id)")
            ));

        $select->where("id = ?", (int)$id_video);

        return $this->fetchRow($select);
    }

    /**
     *
     * @param mixed $id_model
     * @param mixed $type --> '','vod','premieres','contest'
     * @param mixed $private --> 0,1
     * @param mixed $order
     * @param mixed $nr
     * @param mixed $id_show
     * @param mixed $not_type - 'type1,type2' - videos not of this type
     * @param mixed $active
     */
    function getVideos(
        $id_model = null,
        $type = null,
        $private = null,
        $order = null,
        $nr = null,
        $id_show = null,
        $not_type = null,
        $search = null,
        $active = true
    )
    {

        $entity = Videos\Entity\Video::class;
        switch ($type) {
            case 'vod':
                $entity = Videos\Entity\VodVideo::class;
            break;
            case 'premieres':
                $entity = Videos\Entity\PremiereVideo::class;
            break;
            case 'pledge':
                $entity = Videos\Entity\PledgeVideo::class;
            break;
            case 'profile':
                $entity = Videos\Entity\ProfileVideo::class;
            break;
            case 'shows':
                $entity = Videos\Entity\ShowVideo::class;
            break;
        }

        $select = $this->select()->setIntegrityCheck(false)
            ->from(array("v" => "video"), "*")
            ->from(array("m" => "user"), ['username'])
            ->where("m.status=" . Status::ACTIVE)
            ->where("v.user_id=m.id")
            ->joinLeft(["c" => 'photos'], 'v.cover=c.id and c.entity=\'Videos\\\Entity\\\VideoCoverImage\'',
                ['filename as cover'])
            ->group("v.id");
            if ($order) $select->order($order);

        if ($active) $select->where("v.status=" . Status::ACTIVE);
        if ($id_model) $select->where("v.id=?", $id_model);
        if ($type) $select->where("v.entity=?", $entity);
        //if($type) $select->where("v.type=?",$type);
        if ($not_type) $select->where("coalesce(find_in_set(v.type, ?), 0) = 0", $not_type);
//        if($private) $select->where("v.private=?",$private);
//        if($id_show) $select->where("v.id_show=?",$id_show);

        if ($search) {
            $select->where($this->getAdapter()->quoteInto('v.title like ?', strtolower("%" . $search . "%")) . " OR " . $this->getAdapter()->quoteInto('v.tags like ?', strtolower("%" . $search . "%")) . " OR " . $this->getAdapter()->quoteInto('m.screen_name like ?', strtolower("%" . $search . "%")));
        }

        $select->joinLeft(array("r" => "reviews"),
            "v.id=r.resource_id AND r.resource_type='video' AND r.active=1",
            array("total_active_reviews" => new Zend_Db_Expr("count(r.id)")
            ));

        /*if($type == "pledge"){
            $select->where("resource_id IS NULL");
        }*/

        if (!is_null($nr)) $select->limit($nr, 0);
        if ($nr == 1)
            return $this->fetchRow($select);
        else
            return $this->fetchAll($select);
    }

    function getRelatedVideos($id_model = null, $type = null, $private = null, $order, $nr = null)
    {

        $entity = 'Videos\Entity\Video';
        if ($type == 'vod') {
            $entity = 'Videos\Entity\VodVideo';
        }

        //@todo: related by tags, category
        $select = $this->select()->setIntegrityCheck(false)
            ->from(array("v" => "video"), "*")
            ->from(array("m" => "user"), array("screen_name"))
            ->joinLeft(["c" => 'photos'], 'v.cover=c.id and c.entity=\'Videos\\\Entity\\\VideoCoverImage\'',
                ['filename as cover'])
            ->where("m.active=1")
            ->where("v.id=m.id")
            ->where("v.status=1")
            ->group("v.id")
            ->order($order);

        if (!is_null($nr)) $select->limit($nr, 0);

        if ($id_model) $select->where("v.id=?", $id_model);
        //if($type) $select->where("v.type=?",$type);
        if ($type) $select->where("v.entity=?", $entity);
        if ($private) $select->where("v.private=?", $private);

        $select->joinLeft(array("r" => "reviews"),
            "v.id=r.resource_id AND r.resource_type='video' AND r.active=1",
            array("total_active_reviews" => new Zend_Db_Expr("count(r.id)")
            ));

        return $this->fetchAll($select);
    }


    function addPaidVideo($id_user, $id_video, $amount)
    {
        $this->getAdapter()->query("insert into user_paid_videos set " . $this->getAdapter()->quoteInto("id_user=?", $id_user) . " ,
                                           " . $this->getAdapter()->quoteInto("id_video=?", $id_video) . " ,
                                           " . $this->getAdapter()->quoteInto("amount=?", $amount)
        );
    }

    function checkUserPaidVideo($id_video, $id_user = NULL)
    {

        if (!Auth::isUser()) return 0;

        $id_user = $id_user ? $id_user : $_SESSION['user']['id'];

        $result = $this->fetchRow($this->select()
            ->setIntegrityCheck(false)
            ->from(array("p" => "user_paid_videos"), "*")
            ->where("id_user=?", $id_user)
            ->where("id_video=?", $id_video)
        );

        return $result->id;

    }

    function getUserPaidVideos($id_user, $id_model = NULL)
    {

        $result = $this->select()->setIntegrityCheck(false)
            ->from(array("p" => "user_paid_videos"))
            ->from(array("v" => "video"), "*")
            ->where("p.id_video=v.id")
            ->where("p.id_user=?", $id_user)
            ->where("v.status=1");

        if ($id_model) $result->where("v.id=?", $id_model);

        $result->joinLeft(array("r" => "reviews"),
            "v.id=r.resource_id AND r.resource_type='video' AND r.active=1",
            array("total_active_reviews" => new Zend_Db_Expr("count(r.id)")
            ));

        return $this->fetchAll($result);
    }

    public function getUnconverted()
    {
        $select = $this->select()->where("state = ?", "0");
        return $this->fetchAll($select);
    }
}

class VideoRow extends Zend_Db_Table_Row
{

    function getCover()
    {
        return $this->cover;
    }

    function getCaptures()
    {
        if (!$this->id) {
            return false;
        }
        $video = new Video();
        $select = $video->select()
            ->setIntegrityCheck(false)
            ->from(array("p" => "photos"), "*")
            ->join(array('v' => 'video'), 'v.id = p.reference_id', array('cover' => 'p.filename'))
            ->where("p.reference_id = ?", $this->id)
            ->where("p.entity =?", Videos\Entity\VideoCaptureImage::class)
            ->where("p.status =?", 1)
            ->order("p.id DESC");

        return $video->fetchAll($select);

    }

    function getVideoFile()
    {
        return $this->filename;
    }

    function getDuration($format = false)
    {

        if ($format == 'semi') {
            return gmdate('i:s', $this->duration);
        } else {
            return gmdate('H:i:s', $this->duration);
        }
    }

    function getDateAdded($format = false)
    {

        if ($format == 'full') {
            return $this->uploaded_on;
        } else {
            return substr($this->uploaded_on, 0, 10);
        }

    }

    function getVideoCost($type = \Videos\Entity\VodVideo::class)
    {

        $video = new Video;

        if ($video->checkUserPaidVideo($this->id)) {
            return "Already Bought";
        }

        switch ($type) {
            case \Videos\Entity\VodVideo::class:
                $cost = '$' . number_format(config()->tokens_per_second * $this->duration, 2);
                break;
            default:
                $cost = null;
        }
        return $cost;
    }


    function getIdModerator($model_moderator)
    {

        if ($model_moderator instanceof Model_moderator) {
            $moderator = $model_moderator;
            $result = $moderator->fetchRow(
                $moderator->select()->where(db()->quoteInto("id_model= ?", $this->id_model))
            );
            return $result->id_moderator;
        }
    }

}