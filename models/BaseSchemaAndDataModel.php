<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

require_once(__DIR__ . '/AbstractBricksModel.php');

/*
    Общая модель для схем и блоков.
*/

class BaseSchemaAndDataModel extends AbstractBricksModel {
    /**
     * Получение одной записи по полю - значению.
     */

    public function getRowBy($field, $value)
    {
        $row = parent::getRowBy($field, $value);

        $this->_prepareOutgoingData($row);

        return $row;
    }


    /**
     * Получение множества записей по полю - значению.
     */

    public function getAllBy($field, $value)
    {
        $all = parent::getAllBy($field, $value);

        foreach ($all as $key => $row) {
            $this->_prepareOutgoingData($all[$key]);
        }

        return $all;
    }


    /**
     * Получение всех записей.
     */

    public function all()
    {
        $queryResult = parent::all();

        foreach ($queryResult as $key => $item) {
            if ($queryResult[$key]['fields']) {
                $queryResult[$key]['fields'] = json_decode($queryResult[$key]['fields'], true);
            }
        }

        return $queryResult;
    }


    /**
     * Добавление в таблицу.
     */

    public function create($data)
    {
        $this->_prepareIncomingData($data);

        return parent::create($data);
    }


    /**
     * Обновление записи.
     */

    public function update($id, $data)
    {
        $this->_prepareIncomingData($data);

        parent::update($id, $data);
    }


    /**
     * Подготовка входящих в таблицу данных. (Преобразование массива полей в json).
     */

    protected function _prepareIncomingData(&$data)
    {
        if (isset($data['fields']) && !empty($data['fields'])) {
            $data['fields'] = json_encode($data['fields'], JSON_UNESCAPED_UNICODE);
        }
    }


    /**
     * Подготовка исходящих из таблицы данных. (Преобразование json в массив полей).
     */

    protected function _prepareOutgoingData(&$data)
    {
        if (isset($data['fields']) && !empty($data['fields'])) {
            $data['fields'] = json_decode($data['fields'], true);
        }
    }
}