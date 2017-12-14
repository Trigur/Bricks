<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

use TrigurPackage\Traits\Controllers\PositionHandleTrait;
use TrigurPackage\Traits\Controllers\AjaxResponseTrait;

require_once(__DIR__ . '/AbstractBricksAdminController.php');
require_once(__DIR__ . '/../ValidationMethodsTrait.php');

class BricksAdminDataController extends AbstractBricksAdminController
{
    use ValidationMethodsTrait;
    use PositionHandleTrait;
    use AjaxResponseTrait;

    protected static $controllerNameSegment = 'data';
    protected static $credentials = [
        'name', 'title', 'fields', 'schema_id', 'group_id'
    ];

    protected $baseControllerModel;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('BricksDataModel');
        $this->load->model('BricksSchemaModel');
        $this->load->model('BricksGroupModel');
    }

    protected function _getControllerBaseModel()
    {
        return $this->BricksDataModel;
    }

    public function index()
    {
        $this->_render('bricks', [
            'schemas' => $this->_idToKey($this->BricksSchemaModel->all()),
            'groups'  => $this->_idToKey($this->BricksGroupModel->all()),
            'bricks'  => $this->BricksDataModel->getAllSortedByPosition()
        ]);
    }

    public function create($schemaId = false)
    {
        if (! ($schemaId && ($schema = $this->BricksSchemaModel->get($schemaId)))) {
            $this->_redirect($this->_controllerPath('/'));
        }

        if ($this->_isFormRequest() && $this->_brickValidOrExitWithErrors()) {
            $id = $this->BricksDataModel->create($this->_credentials());

            $this->_formRedirect($id);
        }

        $this->_render('brick', [
            'type'        => 'create',
            'schema'      => $schema,
            'groups'      => $this->_idToKey($this->BricksGroupModel->all()),
            'fieldsTypes' => $this->BricksSchemaModel->getFieldsTypes(),
            'fieldsPath'  => $this->_getFieldsTemplatesPath()
        ]);
    }

    public function edit($id = false)
    {
        if (! ($id && ($brick = $this->BricksDataModel->get($id)))) {
            $this->_redirect($this->_controllerPath('/'));
        }

        if (! ($schema = $this->BricksSchemaModel->get($brick['schema_id']))) {
            $this->_redirect($this->_controllerPath('/'));
        }

        if ($this->_isFormRequest() && $this->_brickValidOrExitWithErrors($id)) {
            $data = $this->_credentials();

            $this->BricksDataModel->update($id, $data);
            $this->load->model('BricksRelationsModel')->updateBy([
                'brick_id' => $id,
                'group_id' => $brick['group_id']
            ], [
                'group_id' => $data['group_id'] ?: ''
            ]);

            $this->_formRedirect($id);
        }

        $this->_render('brick', [
            'type'       => 'edit',
            'brick'      => $brick,
            'schema'     => $schema,
            'groups'     => $this->_idToKey($this->BricksGroupModel->all()),
            'fieldsPath' => $this->_getFieldsTemplatesPath()
        ]);
    }

    public function setGroup()
    {
        $val = $this->form_validation;
        $val->set_rules('brick_id', lang('Id блока', 'bricks'), 'required|is_natural_no_zero|callback__checkIdExist');
        $val->set_rules('group_id', lang('Id группы', 'bricks'), 'integer');

        if (! $val->run($this)) {
            $this->_ajaxResponse('error', $this->_getValidationErrorString());
        }

        $brickId = $this->input->post('brick_id');
        $groupId = $this->input->post('group_id');

        $brick = $this->BricksDataModel->get($brickId);

        $this->load->model('BricksRelationsModel')->updateBy([
            'brick_id' => $brickId,
            'group_id' => $brick['group_id']
        ], [
            'group_id' => $groupId ?: ''
        ]);

        $this->BricksDataModel->update($brickId, [
            'group_id' => $groupId ?: '',
        ]);

        $this->_ajaxResponse('success', lang('Группа изменена', 'bricks'));
    }

    private function _brickValidOrExitWithErrors($id = 0)
    {
        $val = $this->form_validation;

        $val->set_rules('name', lang('Название', 'bricks'), 'trim|required|max_length[50]|callback__checkNameAvailable[' . $id .']');
        $val->set_rules('title', lang('Заголовок', 'bricks'), 'trim|required|max_length[100]');
        $val->set_rules('schema_id', lang('Id схемы', 'bricks'), 'required|is_natural_no_zero|callback__checkSchemaIdExist');
        $val->set_rules('group_id', 'Id группы', 'integer');

        if ($val->run($this)) {
            return true;
        }

        $this->_showValidationErrorMessages($val->getErrorsArray());
    }

    private function _getFieldsTemplatesPath()
    {
        return __DIR__ . '/../assets/admin/fields';
    }
}