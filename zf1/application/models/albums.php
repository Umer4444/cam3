<?php

class Albums extends App_Model
{

    protected $_name = "albums";

    protected $_primary = "id";

    protected $_rowClass = 'AlbumRow';

    public function getAlbums($id_model = null, $active = 1, $parent_id = null, $limit = 20, $start = 0, $type = 'all', $id_resource = null, $order = null)
    {

        $select = $this->select()->from(array("a" => $this->_name), array("*"))->setIntegrityCheck(false)
            ->order("a.id desc")
            ->limit($limit, $start);

        if ($id_model) {
            $select->where("a.model_id=?", $id_model);
        }

//        if ($viewable) {
//            $select->where("a.viewable <= " . mktime(0, 0, 0, date("n", time()), date("j", time()), date("Y", time())) . " OR a.viewable = 0");
//        }

        if ($active) {
            $select->where("a.status=?", 1);
        }

        if ($type != 'all') {
            $select->where('a.type=?', $type);
        }

        /*if (!$id_resource) {
            $select->where(new Zend_Db_Expr("a.id_resource IS NULL OR a.id_resource = 0"));
        } else {
            $select->where('a.id_resource=?', $id_resource);
        }*/

        if (!is_null($parent_id)) {
            //$select->where("a.parent_id = ?", $parent_id);
        }

/*        $select->joinLeft(array("r" => "reviews"),
            "a.id=r.resource_id AND r.resource_type='gallery' AND r.active=1",
            array("total_active_reviews" => new Zend_Db_Expr("count(r.id)")
            ));*/

        $select->joinLeft(array("c" => "photos"),"a.cover=c.id", array('cfilename' => 'filename'));

        $select->joinLeft(array("m" => "user"), "a.model_id = m.id", array("screen_name" => new Zend_Db_Expr("COALESCE
        (m.screen_name,'anonymous')")));

        $select->group(array("a.id"));

        if ($order) {
            $select->order($order);
        }

        return $this->fetchAll($select);

    }

    public function getAlbum($id_gallery, $viewable = NULL, $active = 1, $id_model = null, $type = null, $id_resource = null)
    {

        $select = $this->select()->from(array("a" => $this->_name))->setIntegrityCheck(false)
            ->where("a.id=?", $id_gallery);

        //if ($viewable) $select->where("a.viewable>=" . mktime(0, 0, 0, date("n", time()), date("j", time()), date("Y", time())) . " OR a.viewable=0");
        //if ($active) $select->where("a.status=?", 1);
        if ($id_model) $select->where("a.model_id=?", (int)$id_model);
        if ($type) $select->where("a.type=?", $type);
        if ($id_resource) $select->where("a.id_resource=?", $id_resource);

        $select->joinLeft(array("m" => "user"), "a.model_id = m.id", array("screen_name" => new Zend_Db_Expr("COALESCE
        (m.screen_name,'anonymous')")));
        $select->joinLeft(array("mm" => "model_moderator"), "a.model_id = mm.id_model",
            array("id_moderator" => "id_moderator"));
        $select->group(array("a.id"));

        return $this->fetchRow($select);
    }

    public function getCarouselPhotos($id_album = null)
    {
        if (!$id_album) return false;
        $select = $this->select()->from(array("a" => "albums"), array())->setIntegrityCheck(false)
            ->joinLeft(array("p" => "photos"), "a.id = p.album_id", array("*"))
            ->where("a.id=?", $id_album);

        return $this->fetchAll($select);
    }

    public function insertItems($items = null)
    {
        if (!$items || !is_array($items)) return null;
        return $this->insert($items);
        //db()->insert($this->_name, $items);
    }

}

class AlbumRow extends Zend_Db_Table_Row
{

    function getCover($default = "model_album_cover")
    {

        return $this->filename;

        if ($this->cover) {
            //return realpath(APPLICATION_PATH . '/../../public/uploads/images')."/".$this->cover;
            return config()->default_upload_dir . '/' . $this->cover;
        } else {

            return config()->model_album_cover;
        }
    }

}
