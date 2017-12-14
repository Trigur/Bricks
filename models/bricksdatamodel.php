<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

use TrigurPackage\Traits\Models\PositionModelTrait;
require_once(__DIR__ . '/BaseSchemaAndDataModel.php');

/*
    Модель блоков
*/

class BricksDataModel extends BaseSchemaAndDataModel {
    /**
     * Отвечает за обработку позиции записи.
     */

    use PositionModelTrait;


    /**
     * Название таблицы, за которую отвечает модель.
     */

    protected static $table = 'mod_bricks_data';


    /**
     * Добавление блока в таблицу.
     */

    public function create($data)
    {
        $this->addLastPosition($data);
        return parent::create($data);
    }


    /**
     * Удаление блоков по полю - значению.
     */

    public function removeBy($field, $value)
    {
        if ($field === 'id') {
            $id = $value;
        }
        else {
            $block = $this->getRowBy($field, $value);
            if (! $block) return;

            $id = $block['id'];
        }

        $this->load->model('BricksRelationsModel')->removeByBrickId($id);
        parent::removeBy($field, $value);
    }
}