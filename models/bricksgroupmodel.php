<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

use TrigurPackage\Traits\Models\PositionModelTrait;
require_once(__DIR__ . '/AbstractBricksModel.php');

/*
    Модель групп
*/

class BricksGroupModel extends AbstractBricksModel {
    /**
     * Отвечает за обработку позиции записи.
     */

    use PositionModelTrait;


    /**
     * Название таблицы, за которую отвечает модель.
     */

    protected static $table = 'mod_bricks_groups';


    /**
     * Добавление группы в таблицу.
     */

    public function create($data)
    {
        $this->addLastPosition($data);
        return parent::create($data);
    }
}