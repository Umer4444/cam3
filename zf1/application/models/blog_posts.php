<?php
class Blog_posts extends App_Model {

    protected $_name = "blog_posts";

    protected $_primary = "id";

    protected $_rowClass = 'BlogRow';

    private $blog_photo_path = '/uploads/picture/';

    public function getAllPosts($type = null, $id_model=null, $order_date = true, $status = true, $id_category = false, $limit = false) {


        $string = '';
        if(is_array($type) && !empty($type) )  {
            $string = "  WHERE ( ";
            $i=0;
            foreach($type as $t) {
                $i++;
                if($i>1) $string .= " OR ";
                $string .= "baj.type = '{$t}'";

            }
            $string .= ")";
        }



        $cond = "";
        /*if($order_date)
            $cond .=  " AND ba.date > 0  AND ba.date<".time() ;*/


        $subquery = '(SELECT baj.id_post,baj.date,baj.type, baj.chips FROM blog_access as baj   ' . $string . ' ORDER BY baj.date DESC LIMIT 1)';

        if($status)
            $cond .=  " AND bp.status = 1" ;
        if($id_category)
            $cond .=  " AND bp.category = ".(int)$id_category ;


        $select = $this->select()
                        ->from(array("bp" => "blog_posts"), array(
                                                                    "id"        => "id",
                                                                    "title"     => "title",
                                                                    "content"   => "content",
                                                                    "tags"      => "tags",
                                                                    "reposts"   => "reposts",
                                                                    "user"  => "user",
                                                                    "status"    => "status",
                                                                    "category"  => "category",
                                                                )
                        )

                        ->setIntegrityCheck(false);




        $select->group("bp.id");

        /*if($string)
            $select->join(array("ba" => new Zend_Db_Expr($subquery)),
                        "ba.id_post=bp.id",
                        array("*")
                    )  ;
        else{
            $select->joinLeft(array("ba" => "blog_access"),
                        "ba.id_post=bp.id",
                        array(
                            "date" => "date",
                            "type" => "type",
                            "chips" => "chips",
                        )
                    )  ;
        }*/
        $select->joinLeft(
                                array("p" => "photos"),
                                "p.reference_id = bp.id AND p.type='".\Images\Entity\Photo::SMALL_COVER."'",
                                array("small_cover"=> new Zend_Db_Expr("COALESCE(p.filename, '" . config()->model_album_cover . "')"))
                        )
                    ->joinLeft(
                                    array("pp" => "photos"),
                                    "pp.reference_id = bp.id AND pp.type='".\Images\Entity\Photo::BIG_COVER."'",
                                    array("full_cover"=> new Zend_Db_Expr("COALESCE(pp.filename, '" . config()->model_album_cover . "')"))
                                )
                    ->joinLeft(
                                array("bc" => "categories"),
                                "bc.id = bp.category AND bc.user=bp.user AND bc.entity='blog'",
                                array("category_title"=>"name")
                                )
                    ->joinLeft(
                                array("m" => "user"),
                                "bp.user = m.id ",
                                array("screen_name"=>"screen_name")
                                )
                    ->joinLeft(
                                array("mm" => "model_moderator"),
                                "mm.id_model = m.id ",
                                array("id_moderator"=> new Zend_Db_Expr("COALESCE(id_moderator, null)"))
                                )
                    ;
/*
       if (Auth::isUser()) {
            $select->joinLeft(
                            array("bb" => "blog_purchase"),
                            "bb.id_user = " . user()->id ." AND bb.user_type='user' AND bp.id=bb.id_post",
                            array("purchased"=> "bb.id_user")
            );
        }
        if( Auth::isModel()) {
            $select->joinLeft(
                                array("bb" => "blog_purchase"),
                                "bp.user = " . user()->id ." AND bb.user_type='model' AND bp.id=bb.id_post",
                                array("purchased"=> "bb.id_user")
            );
        }*/

        if($limit)
            $select->limit((int)$limit);

       if($id_category)
            $select->where("bp.category=?", $id_category);

        if($id_model)
            $select->where("bp.user=?", $id_model);

        //$select->order(new Zend_Db_Expr("ba.date DESC"));
        $select->order(new Zend_Db_Expr("bp.id DESC")) ;


        return $this->fetchAll($select);
    }

    public function getById($id_post = null, $id_model = null) {
        if(!$id_post) return false;


        $select = $this->select()
                        ->from(array("bp" => "blog_posts"), array(
                                                                    "id"        => "id",
                                                                    "title"     => "title",
                                                                    "content"   => "content",
                                                                    "tags"      => "tags",
                                                                    "reposts"   => "reposts",
                                                                    "pinned"   => "pinned",
                                                                    "featured"   => "featured",
                                                                    //"createdby" => "createdby",
                                                                    "user"  => "user",
                                                                    "category"  => "category",
                                                                    "status"    => "status",
                                                                )
                        )
                        ->setIntegrityCheck(false);

        if($id_model)
            $select->where("bp.user=?", $id_model);

        $select->where("bp.id=?", $id_post);

        $select->group("bp.id");


        $select/*->joinLeft(
                        array("ba" => "blog_access"),
                        "bp.id = ba.id_post",
                        array( "type"=>new Zend_Db_Expr("GROUP_CONCAT(ba.type)"),
                                "date"=>new Zend_Db_Expr("GROUP_CONCAT(ba.date)"),
                                "chips"=>new Zend_Db_Expr("GROUP_CONCAT(ba.chips)")
                                )
                        )*/
                    ->joinLeft(
                                array("p" => "photos"),
                                "p.reference_id = bp.id AND p.type='".\Images\Entity\Photo::SMALL_COVER."'",
                                array("small_cover"=> new Zend_Db_Expr("COALESCE(p.filename, '" . config()->model_album_cover . "')"))
                        )
                    ->joinLeft(
                                    array("pp" => "photos"),
                                    "pp.reference_id = bp.id AND pp.type='".\Images\Entity\Photo::BIG_COVER."'",
                                    array("full_cover"=> new Zend_Db_Expr("COALESCE(pp.filename, '" . config()->model_album_cover . "')"))
                                )
                    ->joinLeft(
                                array("bc" => "categories"),
                                "bc.id = bp.category AND bc.user=bp.user AND bc.entity='blog'",
                                array("category_title"=>"name")
                                )

                    ->joinLeft(
                                array("m" => "user"),
                                "bp.user = m.id",
                                array("screen_name"=>"screen_name")
                                )
                    /*->joinLeft(
                                array("mm" => "model_moderator"),
                                "mm.id_model = bp.user",
                                array("id_moderator"=> new Zend_Db_Expr("COALESCE(id_moderator, null)"))
                                )*/
                    ;

/*        if (Auth::isUser()) {
            $select->joinLeft(
                            array("bb" => "blog_purchase"),
                            "bb.id_user = " . user()->id ." AND bb.user_type='user' AND bp.id=bb.id_post",
                            array("purchased"=> "id_user")
            );
        }
        if( Auth::isModel()) {
            $select->joinLeft(
                                array("bb" => "blog_purchase"),
                                "bb.id_user = " . user()->id ." AND bb.user_type='model' AND bp.id=bb.id_post",
                                array("purchased"=> "id_user")
            );
        }   */
        if(Auth::isLogged()){
            $where = db()->quoteInto("pc.user_id=?", $_SESSION["user"]["id"]);
            $where .= " AND " . db()->quoteInto("pc.user_type=?", $_SESSION["group"]);
            $where .= " AND " . db()->quoteInto("pc.user_type=?", $_SESSION["group"]);
            $where .= " AND " . "pc.content_type='blog_post'";
            $where .= " AND " . "pc.id_content=bp.id";

            /*$select->joinLeft(
                                    array("pc" => "purchased_content"),
                                    $where,
                                    array("purchased"=> "user_id")
                );*/
        }
        $select->order(new Zend_Db_Expr("bp.id DESC")) ;
        //$select->order(new Zend_Db_Expr("ba.date DESC")) ;
  //p($select."",1);

        return $this->fetchRow($select);


    }

    // neterminata
    public function getPostsList(array $options){
        if(!is_array($options) || empty($options) || !isset($options["params"])) return null;

        $type = array();
        if(Auth::isUser()) $type[] = "members";
        if(Auth::isModel()) $type[] = "performers";
        if(Auth::isLogged()) $type[] = "everyone";


    }


}

class BlogRow extends Zend_Db_Table_Row{

    function getIdModerator($model_moderator){
        if($model_moderator instanceof Model_moderator) {
            $moderator =  $model_moderator;

            $result = $moderator->fetchRow(
                    $moderator->select()->where(db()->quoteInto("id_model=".$this->user))

                );
            return $result->id_moderator;
        }
    }
}