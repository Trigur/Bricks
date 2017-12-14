<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

trait ValidationMethodsTrait
{
    private $validationTemp = [];


    /**
     *   Проверка на дублирование названия поля.
     */

    public function _checkFieldName($name)
    {
        if (in_array($name, $this->validationTemp)){
            $this->form_validation->set_message('_checkFieldName', 'Нельзя дублировать названия полей');
            return false;
        }

        $this->validationTemp[] = $name;

        return true;
    }


    /**
     *   Проверка типа поля на существование.
     */

    public function _checkFieldType($type)
    {
        $availableTypes = $this->BricksSchemaModel->getFieldsTypes();

        if (in_array($type, $availableTypes)) {
            return true;
        }

        $this->form_validation->set_message('_checkFieldType', 'Некорректный тип поля');
        return false;
    }


    /**
     *   Проверка типа поля на наличие в бд.
     */

    public function _checkSchemaIdExist($id)
    {
        if ($schema = $this->BricksSchemaModel->get($id)) {
            return true;
        }

        $this->form_validation->set_message('_checkSchemaIdExist', 'Указан несуществующий id для поля "%s%"');
        return false;
    }


    /**
     *   Проверка id на наличие в бд.
     */

    public function _checkIdExist($id)
    {
        if ($schema = $this->_getControllerBaseModel()->get($id)) {
            return true;
        }

        $this->form_validation->set_message('_checkIdExist', 'Указан несуществующий id для поля "%s%"');
        return false;
    }


    /**
     *   Проверка id на существование.
     */

    public function _checkNameAvailable($name, $id)
    {
        if ($this->_getControllerBaseModel()->nameAvailable($name, $id)) {
            return true;
        }

        $this->form_validation->set_message('_checkNameAvailable', 'Имя уже занято');
        return false;
    }


    /**
     *   Получение ошибок валидации в виде html.
     */

    protected function _getValidationErrorString()
    {
        return $this->form_validation->error_string('<p>', '</p>');
    }


    /**
     *   Выводит сообщения об ошибках валидации и выходит.
     */

    protected function _showValidationErrorMessages($errors)
    {
        if (! is_array($errors)) {
            $errors = [$errors];
        }

        foreach ($errors as $errorMessage) {
            showMessage($errorMessage, false, 'r');
        }

        exit();
    }
}