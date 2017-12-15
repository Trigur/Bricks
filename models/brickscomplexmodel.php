<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/*
    Модель для комплексных запросов
*/

class BricksComplexModel extends CI_Model
{
    protected static $relationsTable = 'mod_bricks_relations';
    protected static $dataTable      = 'mod_bricks_data';
    protected static $schemasTable   = 'mod_bricks_schemas';
    protected static $groupsTable    = 'mod_bricks_groups';


    /**
     * Получение множества блоков.
     *
     * @param string  $type      - тип контента
     * @param integer $id        - id контента
     * @param string  $groupName - название группы
     *
     * @return array
     */

    public function getByContent($type, $id, $groupName = false)
    {
        $query = $this->db
            ->from(static::$relationsTable . ' t1')
            ->join(static::$dataTable . ' t2', 't1.brick_id = t2.id')
            ->join(static::$schemasTable . ' t3', 't2.schema_id = t3.id')
            ->select('t2.name, t2.title, t2.fields, t3.name as tpl')
            ->where('t1.item_type', $type)
            ->where('t1.item_id', $id);

        if ($groupName) {
            $query
                ->join(static::$groupsTable . ' t4', 't1.group_id = t4.id')
                ->where('t4.name', $groupName);
        }

        return $query->get()->result_array();
    }


    public function getByGroup($groupName = false)
    {
        $query = $this->db
            ->from(static::$dataTable . ' t1')
            ->join(static::$schemasTable . ' t2', 't1.schema_id = t2.id')
            ->join(static::$groupsTable . ' t3', 't1.group_id = t3.id')
            ->select('t1.name, t1.title, t1.fields, t2.name as tpl')
            ->where('t3.name', $groupName);

        return $query->get()->result_array();
    }
}
