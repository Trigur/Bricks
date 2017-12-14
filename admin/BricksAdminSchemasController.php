<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

require_once(__DIR__ . '/AbstractBricksAdminController.php');
require_once(__DIR__ . '/../ValidationMethodsTrait.php');

class BricksAdminSchemasController extends AbstractBricksAdminController
{
    use ValidationMethodsTrait;

    protected static $controllerNameSegment = 'schemas';
    protected static $credentials = [
        'name', 'title', 'fields'
    ];

    public function __construct()
    {
        parent::__construct();

        $this->load->model('BricksSchemaModel');
    }

    protected function _getControllerBaseModel()
    {
        return $this->BricksSchemaModel;
    }

    public function index()
    {
        $this->_render('schemas', [
            'schemas' => $this->BricksSchemaModel->all()
        ]);
    }

    public function create()
    {
        if ($this->_isFormRequest() && $this->_schemaValidOrExitWithErrors()) {
            $id = $this->BricksSchemaModel->create($this->_credentials());

            $this->_formRedirect($id);
        }

        $this->_render('schema', [
            'type'        => 'create',
            'fieldsTypes' => $this->BricksSchemaModel->getFieldsTypes()
        ]);
    }

    public function edit($id = false)
    {
        if (! ($id && ($schema = $this->BricksSchemaModel->get($id)))) {
            $this->_redirect($this->_controllerPath('/'));
        }

        if ($this->_isFormRequest() && $this->_schemaValidOrExitWithErrors($id)) {
            $this->BricksSchemaModel->update($id, $this->_credentials());

            $this->_formRedirect($id);
        }

        $this->_render('schema', [
            'type'        => 'edit',
            'schema'      => $schema,
            'fieldsTypes' => $this->BricksSchemaModel->getFieldsTypes()
        ]);
    }

    protected function _credentials()
    {
        $result = parent::_credentials();

        if (isset($result['fields'])) {
            $this->_restructuringFieldsArray($result['fields']);
        }

        return $result;
    }

    private function _schemaValidOrExitWithErrors($id = 0)
    {
        $val = $this->form_validation;

        $val->set_rules('name', lang('Название', 'bricks'), 'trim|required|max_length[50]|callback__checkNameAvailable[' . $id .']');
        $val->set_rules('title', lang('Заголовок', 'bricks'), 'trim|required|max_length[255]');

        if ($fields = $this->input->post('fields')) {
            if (! $this->_incomingDataCorrect()) {
                $this->_showValidationErrorMessages(lang('Техническая ошибка. Обновите страницу.', 'bricks'));
            }

            $fieldsCount = count($fields['name']);

            for ($i = 0; $i < $fieldsCount; $i++) {
                $val->set_rules("fields[name][{$i}]", lang('Название поля', 'bricks'), 'trim|required|callback__checkFieldName|max_length[50]');
                $val->set_rules("fields[title][{$i}]", lang('Заголовок поля', 'bricks'), 'trim|required|max_length[255]');
                $val->set_rules("fields[type][{$i}]", lang('Тип поля', 'bricks'), 'required|callback__checkFieldType');
            }
        }

        if ($val->run($this)) {
            return true;
        }

        $this->_showValidationErrorMessages($val->getErrorsArray());
    }
}