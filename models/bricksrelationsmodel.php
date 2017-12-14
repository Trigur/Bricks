<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/*
    Модель отношений между контентом и блоками
*/

class BricksRelationsModel extends CI_Model {
    /**
     * Название таблицы, за которую отвечает модель.
     */

    protected static $table = 'mod_bricks_relations';


    /**
     * Обновление элемента по нескольким параметрам.
     */

    public function updateBy($whereArray, $data)
    {
        $this->db->where($whereArray);
        $this->db->update(static::$table, $data);
    }


    /**
     * Создание отношений для множества блоков и контента.
     */

    public function makeRelations($itemId, $itemType, $bricksData)
    {
        $this->removeByContent($itemId, $itemType);

        $position = 0;
        foreach ($bricksData as $brickData) {
            $data = [
                'item_id'   => $itemId,
                'item_type' => $itemType,
                'brick_id'  => $brickData['id'],
                'group_id'  => $brickData['group_id'],
                'position'  => $position,
            ];

            $this->db->insert(static::$table, $data);

            $position++;
        }
    }


    /**
     * Получение всех отношений для одного элемента контента.
     */

    public function allByContent($itemId, $itemType)
    {
        return $this->db
            ->where('item_id', $itemId)
            ->where('item_type', $itemType)
            ->order_by('position', 'asc')
            ->get(self::$table)
            ->result_array();
    }


    /**
     * Удаление записи по id.
     */

    public function removeByBrickId($id)
    {
        $this->db
            ->where('brick_id', $id)
            ->delete(self::$table);
    }


    /**
     * Удаление записей по контенту.
     */

    public function removeByContent($itemId, $itemType)
    {
        $this->db
            ->where('item_id', $itemId)
            ->where('item_type', $itemType)
            ->delete(self::$table);
    }
}