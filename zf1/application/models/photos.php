<?php

use PerfectWeb\Core\Utils\Status;

class Photos extends App_Model
{

    protected $_name="photos";

    protected $_primary="id";

    public function getPhotosByModelId($id_model=null, $id_show=null, $type=null) {

        if(!$id_model) {
           return false;
        }

        $select = $this->select()
                   ->from(array("p" => $this->_name), array("*"))
                   ->joinLeft(
                       array(
                           'i' => 'interactions',
                       ),
                       "i.reference_id = p.id AND i.entity = '" . \Images\Entity\Photo::class."' ",
                       array(
                           'rating' => 'rating',
                           'votes' => 'votes',
                           'likes' => 'likes',
                           'dislikes' => 'dislikes'
                       )
                   )
                   ->where("user=?", $id_model)
                   ->order("p.position asc");

        if ($id_show) {

            $select->where("reference_id=?", $id_show);
            $select->where("entity=?", \Application\Entity\Show::class);

        }

        if ($type) {
            $select->where("type=?", $type);
        }

        $select->order("id asc");

        return $this->fetchAll($select);
    }

    public function getPhotosByAlbumId($id_album, $active=Status::ACTIVE, $type = null) {

        if(!$id_album) {
           return false;
        }

        $select = $this->select()->from(array("a" => $this->_name))->setIntegrityCheck(false)
                       ->joinLeft(
                           array(
                               'i' => 'interactions',
                           ),
                           "i.reference_id = a.id AND i.entity = '" . \Images\Entity\Photo::class ."' ",
                           array(
                               'rating' => 'rating',
                               'votes' => 'votes',
                               'likes' => 'likes',
                               'dislikes' => 'dislikes'
                           )
                       )
                       ->where("a.reference_id=?", $id_album)
                       ->where("a.entity=?", 'photos');

        if($type) {
           $select->where("type=?", $type);
        }

        if($active) {
            $select->where("a.status=?", 1);
        }

        $select->joinLeft(array("r" => "reviews"),
                "a.id=r.resource_id AND r.resource_type='image' AND r.active=1",
                array("total_active_reviews" => new Zend_Db_Expr("count(r.id)")
            ));

        $select->joinLeft(array("m" => "user"),"a.user = m.id", array("screen_name" => new Zend_Db_Expr("COALESCE(m
        .screen_name,'anonymous')")));

        $select->group(array("a.id"));

        return $this->fetchAll($select);
    }

    public function getPhotoById($id_photo, $active=null){

       $select = $this->select()->from(array("p" => $this->_name))->setIntegrityCheck(false)
                      ->where("p.id=?", $id_photo)
                      //->order("photos.id asc")
                      ->joinLeft(
                          array(
                              'i' => 'interactions',
                          ),
                          "i.reference_id = p.id AND i.entity = '" . \Images\Entity\Photo::class. "'",
                          array(
                              'rating' => 'rating',
                              'votes' => 'votes',
                              'likes' => 'likes',
                              'dislikes' => 'dislikes'
                          )
                      )
                      ->joinLeft(
                          array("a" => "albums"),
                          "p.reference_id=a.id and p.entity='photos'",
                          array(
                              "viewable" => "viewable",
                              "album_name" => new Zend_Db_Expr("coalesce(a.name, null)")
                          )
                      );

        if($active) {
            $select->where("p.status=?", 1);
        }

        $select->joinLeft(array("r" => "reviews"),
                "p.id=r.resource_id AND r.resource_type='image' AND r.active=1",
                array("total_active_reviews" => new Zend_Db_Expr("count(r.id)")
            ));

        $select->joinLeft(array("mm" => "model_moderator"), "p.user = mm.id_model", array("id_moderator" =>
                                                                                           "id_moderator"));

        $select->group(array("a.id"));

        return $this->fetchRow($select);
    }

    public function addPhoto($arr = null) {

        if(!$arr) {
            return false;
        }

        $error = false;
        $arr["uploaded_on"] = date('Y-m-d H:i:s');

        //($arr["filename"]) ? $arr['filename'] = '/uploads/photos/'.$arr["filename"] : $error = true;

        if(!$error) {
            return $this->insert($arr);
        }
    }

    public function deletePhoto($file = null,$type='photo') {

        if(!$file) {
            return null;
        }

        switch($type) {
            case 'photo':
                //delete all sizes
                unlink(APPLICATION_PATH . '/../../public'.$file);
                unlink(APPLICATION_PATH . '/../../public'.getPhotoThumb($file,'t'));

            break;

            case 'other photo types':

            break;
        }

    }

    public function recentPhotos($id = null, $active = false) {

        $select = $this->select()->from(array("p" => $this->_name))->setIntegrityCheck(false);

        if($id) {
            $select->where("p.user=?", (int)$id);
        }

        if($active) {
            $select->where("p.status=".Status::ACTIVE);
        }

        $select->where("p.user is not null")
            ->order("p.position asc")
            ->joinLeft(
                array(
                    'i' => 'interactions',
                ),
                "i.reference_id = p.id AND i.entity = '" . Images\Entity\Photo::class."'",
                array(
                    'rating' => 'rating',
                    'votes' => 'votes',
                    'likes' => 'likes',
                    'dislikes' => 'dislikes'
                )
            )
            ->joinLeft(array("a" => "albums"),
                "p.reference_id=a.id AND p.entity='Images\\Entity\\Photo' AND (a.viewable<=".mktime(0,0,0, date("n", time()), date("j", time()),date("Y", time()))." OR a.viewable=0) AND (a.password IS NULL OR a.password = '' AND a.amount=0)",
                array("viewable" => "viewable")
            );
        $select->joinLeft(array("r" => "reviews"),
            "p.id=r.resource_id AND r.resource_type='image' AND r.active=".Status::ACTIVE,
            array("total_active_reviews" => new Zend_Db_Expr("count(r.id)")
        ));

        $select->joinLeft(array("m" => "user"),"p.user = m.id", array("screen_name" => new Zend_Db_Expr("COALESCE(m
        .screen_name,'anonymous')")));

        $select->group("p.id");
        if($id) {
            $select->limit(15);
        } else {
            $select->limit(200);
        }

        return $this->fetchAll($select);
    }

    public function multipleAction($idList = null, $action = null) {

        if(!$idList) {
            return false;
        }

        $idList = trim($idList, ",");

        switch ($action) {

            case "delete":
                $files = $this->fetchAll($this->select()->where(" FIND_IN_SET(id, '".$idList."') > 0"));
                $allDeleted = $this->delete(new Zend_Db_Expr(" FIND_IN_SET(id, '".$idList."') > 0"));
                if($allDeleted) {
                    foreach($files as $file)
                          $this->deletePhoto($file->filename, "photo");
                }
                break;
        }

    }

    public function insertItems($items = null) {

        if(!$items || !is_array($items)) {
            return null;
        }

        return $this->insert($items);
    }

    public function updateStatus($status = 0, $id_album = 0, $id_model = 0, $type = 0) {

        return $this->update(array("active" => $status), "reference_id = $id_album AND entity = 'photos' AND
        user=$id_model AND type='$type'");
    }


}
