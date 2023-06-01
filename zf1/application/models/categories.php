<?php

class Categories extends App_Model
{

    protected $_name = "categories";

    protected $_primary = "id";


    function getCategoriesByModel($id_model = null)
    {
        if (!$id_model) return false;
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("c" => "categories"), "c.name")
            ->from(array("link" => "model_to_categories"), "*")
            ->where("link.id_model=?", $id_model)
            ->where("link.id_category = c.id")
            ->order("sort asc");

        return $this->fetchAll($select)->toArray();
    }

    function getCategoriesByVideo($id_video)
    {

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("v" => "video"))
            ->joinLeft(["c" => 'categories'], 'v.category_id=c.id',
                ['c.name'])
            ->where("v.id=?", $id_video)
            ->order("id asc");

        return $this->fetchAll($select)->toArray();
    }

    /**
     *
     * @param mixed $id_model
     * @param mixed $cats
     */
    function addCategoryForModel($id_model, $cats)
    {

        $cats = (array_unique($cats));

        if (!$id_model || !count($cats)) return false;

        $this->getAdapter()->query("delete from model_to_categories where " . $this->getAdapter()->quoteInto("id_model=?", $id_model));

        $i = 0;
        foreach ($cats as $cat) {
            if (!$cat) continue;
            $i++;

            $this->getAdapter()->query("insert into model_to_categories set " . $this->getAdapter()->quoteInto("id_model=?", $id_model) . " ,
                                                                         " . $this->getAdapter()->quoteInto("id_category=?", $cat) . " ,
                                                                         " . $this->getAdapter()->quoteInto("sort=?", $i));
        }
    }

    function getCategoriesArray($type = "model")
    {

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->where("entity=?", $type)
            ->order("name ASC");
        $resultSet = $this->fetchAll($select);

        $ret = array();
        foreach ($resultSet as $result) {
            $ret[$result->id] = $result->name;
        }

        return $ret;
    }

}    