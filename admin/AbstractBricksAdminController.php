<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

use TrigurPackage\Traits\ArrayMethodsTrait;

/*
    Базовый класс контроллеров админки
*/

abstract class AbstractBricksAdminController extends BaseAdminController
{
    /**
     *  Набор трешовых методов идиота для обработки массивов.
     */

    use ArrayMethodsTrait;


    /**
     *  Сегмент-название контроллера в url.
     */

    protected static $controllerNameSegment;

    public function __construct() {
        parent::__construct();

        $lang = new MY_Lang();
        $lang->load('bricks');

        $this->load->library('Form_validation');
    }


    /**
     *  Возвращает основную модель контроллера.
     */

    abstract protected function _getControllerBaseModel();


    /**
     *  Удаление элементов по списку id.
     */

    public function remove()
    {
        $ids = $this->input->post('ids');

        if ($ids) {
            $model = $this->_getControllerBaseModel();

            foreach ($ids as $id) {
                $model->remove($id);
            }
        }

        $this->_redirect($this->_controllerPath('/'));
    }


    /**
     *  Рендер страниц админки.
     */

    protected function _render($tplName, $data)
    {
        \CMSFactory\assetManager::create()
            ->setData($data)
            ->registerScript('admin')
            ->registerStyle('admin')
            ->renderAdmin($tplName);
    }


    /**
     *  Проверка на идиота в админке.
     *  Например при получении данных:
     *  fields['name'][]
     *  fields['title'][]
     *  fields['type'][]
     *  Количество name, title и type должно быть равно.
     */

    protected function _incomingDataCorrect()
    {
        $credentials = $this->_credentials();

        if (! isset($credentials['fields'])) {
            return true;
        }

        $fieldsData = $credentials['fields'];
        $fieldsCount = count();

        if ($fieldsCount < 2) {
            return true;
        }

        $previousCount = false;
        foreach ($fieldsData as $fieldData) {
            $currentCount = count($fieldsData);

            if ($previousCount === false) {
                $previousCount = $currentCount;
                continue;
            }

            if ($currentCount !== $previousCount) {
                return false;
            }
        }

        return true;
    }


    /**
     *  Редирект для обычных и pjax-запросов.
     */

    protected function _redirect($url = false)
    {
        if ($this->input->is_ajax_request() || $this->input->post()) {
            pjax($url);
        }
        else {
            redirect($url);
        }

        exit();
    }


    /**
     *  Редирект при отправке формы.
     */

    protected function _formRedirect($id = false)
    {
        $action = $this->input->post('action');

        if ($action === 'tomain' || $id === false) {
            pjax($this->_controllerPath('/'));
        }
        elseif ($action === 'toedit') {
            pjax($this->_controllerPath("/edit/{$id}"));
        }

        exit();
    }


    /**
     *  Получение полного url для текущего контроллера.
     */

    protected function _controllerPath($url = false)
    {
        $urlArray = [
            '/admin/components/init_window/bricks',
            trim(static::$controllerNameSegment, '/'),
        ];

        if ($url) {
            $urlArray[] = trim($url, '/');
        }

        return implode('/', $urlArray);
    }


    /**
     *  Выборка данных из пост-запроса.
     */

    protected function _credentials()
    {
        $post   = $this->input->post();
        $result = [];

        foreach (static::$credentials as $name) {
            if (isset($post[$name])) {
                $result[$name] = $post[$name];
            }
        }

        return $result;
    }


    /**
     *  Проверка на то, что запрос передает форму.
     */

    protected function _isFormRequest()
    {
        if ($this->input->is_ajax_request()) {
            if (!isset($_POST['nav']) && isset($_POST['action'])) {
                return true;
            }
        }

        return false;
    }
}