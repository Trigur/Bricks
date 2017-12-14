<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/*
    Модель для установки и удаления модуля
*/

class BricksInstallModel extends CI_Model {
    /*
        Названия таблиц
    */

    protected $schemaTable    = 'mod_bricks_schemas';
    protected $dataTable      = 'mod_bricks_data';
    protected $relationsTable = 'mod_bricks_relations';
    protected $groupsTable    = 'mod_bricks_groups';

    public function __construct()
    {
        parent::__construct();

        $this->load->dbforge();
    }

    /**
     * Установка - точка входа.
     */

    public function install()
    {
        $this->_makeSchemaTable();
        $this->_makeDataTable();
        $this->_makeRelationsTable();
        $this->_makeGroupsTable();
        $this->_setComponentSettings();
    }


    /**
     * Создание таблицы схем.
     */

    private function _makeSchemaTable()
    {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
                'unsigned'       => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'fields' => [
                'type' => 'TEXT'
            ],
        ];

        $this->dbforge->add_key('id', true);
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($this->schemaTable, true);
    }


    /**
     * Создание таблицы блоков.
     */

    private function _makeDataTable()
    {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
                'unsigned'       => true,
            ],
            'schema_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'group_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'fields' => [
                'type' => 'TEXT'
            ],
            'position' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ];

        $this->dbforge->add_key('id', true);
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($this->dataTable, true);
    }


    /**
     * Создание таблицы отношений.
     */

    private function _makeRelationsTable()
    {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
                'unsigned'       => true,
            ],
            'brick_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'item_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'item_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'group_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'position' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ];

        $this->dbforge->add_key('id', true);
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($this->relationsTable, true);
    }


    /**
     * Создание таблицы групп.
     */

    private function _makeGroupsTable()
    {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
                'unsigned'       => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'position' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ];

        $this->dbforge->add_key('id', true);
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($this->groupsTable, true);
    }


    /**
     * Устанавливает параметры модуля в таблице компонентов.
     */

    private function _setComponentSettings()
    {
        $this->db
            ->where('name', 'bricks')
            ->update('components', [
                'autoload' => '1',
                'in_menu'  => '1'
            ]);
    }


    /**
     * Удаление таблиц при удалении модуля.
     */

    public function uninstall()
    {
        $this->dbforge->drop_table($this->schemaTable);
        $this->dbforge->drop_table($this->dataTable);
        $this->dbforge->drop_table($this->relationsTable);
        $this->dbforge->drop_table($this->groupsTable);
    }
}