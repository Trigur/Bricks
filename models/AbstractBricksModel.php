<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

use TrigurPackage\Models\AbstractBaseModel;

/*
    Общая модель для схем, блоков и групп.
*/

abstract class AbstractBricksModel extends AbstractBaseModel {
    /**
     * Проверка - доступно ли имя. Игнорирует элемент с указанным id при запросе
     */

    public function nameAvailable($name, $id = false)
    {
        if ($id !== false) {
            $this->db->where('id !=', $id);
        }

        $this->db->where('name', $name);

        $result = $this->db->get(static::$table)->row_array();

        return $result ? false : true;
    }
}