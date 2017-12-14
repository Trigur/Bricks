<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

use TrigurPackage\Traits\Controllers\PositionHandleTrait;
use TrigurPackage\Traits\Controllers\AjaxResponseTrait;

require_once(__DIR__ . '/AbstractBricksAdminController.php');
require_once(__DIR__ . '/../ValidationMethodsTrait.php');

class BricksAdminGroupsController extends AbstractBricksAdminController
{
    use ValidationMethodsTrait;
    use PositionHandleTrait;
    use AjaxResponseTrait;

    protected static $controllerNameSegment = 'groups';
    protected static $credentials = [
        'id', 'title', 'name'
    ];

    public function __construct()
    {
        parent::__construct();

        $this->load->model('BricksGroupModel');
    }

    protected function _getControllerBaseModel()
    {
        return $this->BricksGroupModel;
    }

    public function index()
    {
        if ($this->_isFormRequest() && $this->_groupsValidOrExitWithErrors()) {
            $groups = $this->input->post('groups');
            $this->_restructuringFieldsArray($groups);

            foreach ($groups as $group) {
                $this->BricksGroupModel->update($group['id'], [
                    'name'  => $group['name'],
                    'title' => $group['title']
                ]);
            }

            $this->_formRedirect();
        }

        $this->_render('groups', [
            'groups' => $this->BricksGroupModel->getAllSortedByPosition()
        ]);
    }

    public function add()
    {
        $val = $this->form_validation;
        $val->set_rules('name', lang('Название', 'bricks'), 'trim|required|max_length[50]|callback__checkNameAvailable[]');
        $val->set_rules("title", lang('Заголовок', 'bricks'), 'trim|required|max_length[255]');

        if (! $val->run($this)) {
            $this->_showValidationErrorMessages($val->getErrorsArray());
        }

        $id = $this->BricksGroupModel->create([
            'name'  => $this->input->post('name'),
            'title' => $this->input->post('title'),
        ]);

        $this->_ajaxResponse('success', null, [
            'id' => $id,
        ]);
    }

    private function _groupsValidOrExitWithErrors($id = 0)
    {
        $val = $this->form_validation;

        $groups = $this->input->post('groups');

        if (! $this->_incomingDataCorrect()) {
            $this->_showValidationErrorMessages(lang('Техническая ошибка. Обновите страницу.', 'bricks'));
        }

        $fieldsCount  = count($groups['id']);

        for ($i = 0; $i < $fieldsCount; $i++) {
            $val->set_rules("groups[id][{$i}]", lang('Id группы', 'bricks'), 'callback__checkIdExist');
            $val->set_rules("groups[name][{$i}]", lang('Название', 'bricks'), 'trim|required|max_length[50]|callback__checkNameAvailable[' . $groups['id'][$i] . ']');
            $val->set_rules("groups[title][{$i}]", lang('Заголовок', 'bricks'), 'trim|required|max_length[255]');
        }

        if ($val->run($this)) {
            return true;
        }

        $this->_showValidationErrorMessages($val->getErrorsArray());
    }
}