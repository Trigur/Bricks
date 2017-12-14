<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

require_once(__DIR__ . '/BaseSchemaAndDataModel.php');

/*
    Модель схем
*/

class BricksSchemaModel extends BaseSchemaAndDataModel {
    /**
     * Название таблицы, за которую отвечает модель.
     */

    protected static $table = 'mod_bricks_schemas';


    /**
     * Доступные типы полей для блоков.
     */

    private $fieldTypes = [
        'input',
        'textarea',
        'file',
        'image',
    ];


    /**
     * Возвращает доступные типы полей.
     */

    public function getFieldsTypes()
    {
        return $this->fieldTypes;
    }


    /**
     * Удаление схемы и всех зависимых блоки.
     */

    public function removeBy($field, $value)
    {
        if ($field === 'id') {
            $id = $value;
        }
        else {
            $schema = $this->getRowBy($field, $value);
            if (! $schema) return;

            $id = $schema['id'];
        }

        $this->load->model('BricksDataModel')->removeBy('schema_id', $id);
        parent::removeBy($field, $value);
    }
}