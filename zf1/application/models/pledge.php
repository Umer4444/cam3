<?
class Pledge extends App_Model
{

    protected $_name = "pledge";
    protected $_primary = "id";

    private $photos_dir;
    private $videos_dir;

    public function __construct()
    {
        parent::__construct();
        $this->photos_dir = APPLICATION_PATH . '/../../public/uploads/photos/';
        $this->videos_dir = APPLICATION_PATH . '/../../public/uploads/videos/';
    }

    public function selectAll($id_model = null, $sort = null, $active = true, $filter = array())
    {

        $select = $this->select()
            ->from(array("p" => "pledge"))
            ->setIntegrityCheck(false)
            ->joinLeft(array("ph" => "photos"),
                "p.id = ph.reference_id AND ph.entity= '".addslashes(Images\Entity\PledgeImage::class)."' AND ph.type ='pledge_cover'",
                array("photo_cover" => new Zend_Db_Expr("COALESCE(ph.filename, 'http://placehold.it/120x120')"))
            )
            ->joinLeft(array("m" => "user"),
                "p.id_model = m.id",
                array("model_screen_name" => new Zend_Db_Expr("COALESCE(m.screen_name, m.first_name, 'noname')"))
            )
            ->joinLeft(array("pf" => "funders"),
                "p.id = pf.reference_id AND pf.entity = '".addslashes(Application\Entity\PledgeFunder::class)."'",
                array(
                    "contributors" => new Zend_Db_Expr("COALESCE(COUNT(pf.id),0)"),
                    "contributed_amount" => new Zend_Db_Expr("COALESCE(SUM(pf.amount),0)")
                )
            )
            ->joinLeft(array("pf2" => "funders"),
                "p.id = pf2.reference_id AND pf2.entity = '".addslashes(Application\Entity\PledgeFunder::class)."'",
                array(
                    "last_contribution" => new Zend_Db_Expr("MAX(pf2.date)")
                )
            )
            ->joinLeft(array("c" => "categories"),
                "p.id_category = c.id AND c.entity = 'pledge'",
                array(
                    "category_name" => new Zend_Db_Expr("c.name"),
                )
            );

        if (isset($filter["categories_id"]) && !empty($filter["categories_id"])) {
            //$select->joinRight(array("mcat" => "model_to_categories"), " FIND_IN_SET(mcat.id_category, '".trim($filter["categories"],",")."') AND mcat.id_model=m.id", array(""));
            $select->where("FIND_IN_SET(p.id_category, '" . trim(implode(',', $filter["categories_id"]), ",") . "') ");
        }
        if (isset($filter["orientation"]) && !empty($filter["orientation"])) {
            $select->where("FIND_IN_SET(m.orientation, '" . trim($filter["orientation"], ",") . "') ");
        }

        $select->group("p.id");
        if ($id_model)
            $select->where("p.id_model=?", $id_model);
        if ($active)
            $select->where("p.status=1");
        if ($sort) {
            switch ($sort) {
                case 'new':
                    $order = "p.start_date DESC";
                    break;
                case 'most_funded':
                    $order = "contributors DESC";
                    break;
                case 'ending_soon':
                    $order = "p.end_date DESC";
                    break;
                case 'popular':
                    $order = "last_contribution DESC";
                    break;
                default:
                    $order = $sort;
            }
        } else $order = null;

        if ($order)
            $select->order($order);

        return $this->fetchAll($select);
    }

    public function getById($id = null)
    {

        if (!$id) return false;

        $select = $this->select()
            ->from(array("p" => "pledge"))
            ->setIntegrityCheck(false)
            ->joinLeft(array("ph" => "photos"),
                "p.id = ph.reference_id AND ph.entity='". addslashes(Images\Entity\PledgeImage::class) ."'
                AND ph.type = '".Images\Entity\Photo::PLEDGE_COVER."'",
                array(
                    "photo_cover_big" => new Zend_Db_Expr("ph.filename"),
                    "id_photo_cover" => new Zend_Db_Expr("ph.id"),
                )
            )
            ->joinLeft(array("v" => "video"),
                "p.id = v.reference_id AND v.entity = '". addslashes(Videos\Entity\PledgeVideo::class)."'",
                array(
                    'id_video' => 'id',
                    'video_title' => 'v.title',
                    'filename' => 'v.filename'
                )
            )
            ->joinLeft(array("m" => "user"),
                "p.id_model = m.id",
                array(
                    "model_screen_name" => new Zend_Db_Expr("COALESCE(m.screen_name, m.username, 'performer')"),
                )
            )
            ->joinLeft(array("pf" => "funders"),
                "p.id = pf.reference_id AND pf.entity = '".addslashes(Application\Entity\PledgeFunder::class)."'",
                array(
                    "contributors" => new Zend_Db_Expr("COALESCE(COUNT(pf.id),0)"),
                    "contributed_amount" => new Zend_Db_Expr("COALESCE(SUM(pf.amount),0)")
                )
            )
            ->joinLeft(array("a" => "albums"),
                "p.id = a.id AND a.type = 'pledge'",
                array(
                    "reference_id" => new Zend_Db_Expr("a.id"),
                    "entity" => 'cover',
                )
            )
            ->joinLeft(array("c" => "categories"),
                "p.id_category = c.id AND c.entity = 'pledge'",
                array(
                    "category_name" => new Zend_Db_Expr("c.name"),
                )
            )
            ->joinLeft(array("mm" => "model_moderator"), "m.id = mm.id_model", array("id_moderator" => "id_moderator"))

            ->where("p.id=?", (int)$id)
            ->group("pf.id");

        return $this->fetchRow($select);
    }

    public function deletePledge($id_pledge = null, $photoTable = null)
    {
        if (!$id_pledge) return null;

        $pledge = $this->getById((int)$id_pledge);


        if ($pledge->id_photo_cover && $photoTable instanceof Photos)
            $photoTable->multipleAction($pledge->id_photo_cover, "delete");

        return $this->delete(db()->quoteInto("id=" . (int)$id_pledge));

    }

}
