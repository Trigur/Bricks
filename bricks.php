<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

use \CMSFactory\Events;
use TrigurPackage\Traits\ArrayMethodsTrait;

require_once(__DIR__ . '/ValidationMethodsTrait.php');

/*
    Глоссарий:

    "Контент" - страница или категория
*/

class Bricks extends MY_Controller
{
    /**
     *  Набор трешовых методов идиота для обработки массивов.
     */

    use ArrayMethodsTrait;


    /*
        Методы валидации.
    */

    use ValidationMethodsTrait;

    public function __construct() {
        parent::__construct();

        $this->load->model('BricksDataModel');
        $this->load->model('BricksGroupModel');
        $this->load->model('BricksSchemaModel');
        $this->load->model('BricksRelationsModel');
        $this->load->helper('bricks');
    }


    /**
     * Получение блока по названию.
     *
     * @param array $name - название блока
     * @param array $data - массив с дополнительными данными для блока
     *
     * @return string (html) || null
     */

    public function _getBrickByName($name, $data = null)
    {
        return $this->_getBy('name', $name);
    }


    /**
     * Получение блоков текущей страницы или категории.
     *
     * @param array  $content   - массив с данными страницы или категории
     * @param string $groupName - название группы блоков
     * @param array  $data      - массив с дополнительными данными для блока
     * @param string $prefix    - html-префикс при рендере блока
     * @param string $suffix    - html-суффикс при рендере блока
     *
     * @return string (html)
     */

    public function _getContentBricks($content = null, $groupName = false, $data = null, $prefix = '', $suffix = '')
    {
        if ($pageId = $this->_getPageId($content)) {
            $bricks = $this->_getPageBricks($pageId, $groupName, $data, $prefix, $suffix);

            if ($bricks) {
                return $bricks;
            }
        }

        if ($categoryId = $this->_getCategoryId($content)) {
            $bricks = $this->_getCategoryBricks($categoryId, $groupName, $data, $prefix, $suffix);

            if ($bricks) {
                return $bricks;
            }
        }
    }


    /**
     * Получение блоков категории.
     *
     * @param array  $categoryId - id категории
     * @param string $groupName  - название группы блоков
     * @param array  $data       - массив с дополнительными данными для блока
     * @param string $prefix     - html-префикс при рендере блока
     * @param string $suffix     - html-суффикс при рендере блока
     *
     * @return string (html)
     */

    public function _getCategoryBricks($categoryId = false, $groupName = false, $data = null, $prefix = '', $suffix = '')
    {
        return $this->_getBricks(
            'category',
            $categoryId ?: $this->_getCategoryIdFromCore(),
            $groupName,
            $data,
            $prefix,
            $suffix
        );
    }


    /**
     * Получение блоков страницы.
     *
     * @param array  $pageId    - id страницы
     * @param string $groupName - название группы блоков
     * @param array  $data      - массив с дополнительными данными для блока
     * @param string $prefix    - html-префикс при рендере блока
     * @param string $suffix    - html-суффикс при рендере блока
     *
     * @return string (html)
     */

    public function _getPageBricks($pageId = false, $groupName = false, $data = null, $prefix = '', $suffix = '')
    {
        return $this->_getBricks(
            'page',
            $pageId ?: $this->_getPageIdFromCore(),
            $groupName,
            $data,
            $prefix,
            $suffix
        );
    }


    /**
     * Общая логика для получения одного блока по параметрам.
     */

    private function _getBy($fieldName, $value, $data = null)
    {
        $brick = $this->BricksDataModel->getRowBy($fieldName, $value);

        if (!$brick) {
            return;
        }

        $schema = $this->BricksSchemaModel->get($brick['schema_id']);

        return $this->_renderBrick($schema['name'], $brick, $data);
    }


    /**
     * Общая логика для рендера блоков.
     */

    private function _getBricks($type, $id, $groupName = false, $data = null, $prefix, $suffix)
    {
        $bricks = $this->load->model('BricksComplexModel')->get($type, $id, $groupName);

        foreach ($bricks as $key => $brick) {
            if ($brick['fields']) {
                $bricks[$key]['fields'] = json_decode($brick['fields'], true);
            }
        }

        if (! $bricks) return;

        return $this->_renderBricks($bricks, $data, $prefix, $suffix);
    }


    /**
     * Рендер множества блоков.
     */

    private function _renderBricks($bricks, $data = null, $prefix = '', $suffix = '')
    {
        $html = '';
        foreach ($bricks as $brick) {
            $html .= $prefix . $this->_renderBrick($brick['tpl'], $brick, $data) . $suffix;
        }

        return $html;
    }


    /**
     * Рендер одного блока.
     */

    private function _renderBrick($template, $brick, $data = null)
    {
        if ($brick['fields']) {
            $fields = $brick['fields'];
            unset($brick['fields']);
            $brick = array_merge($brick, $fields);
        }

        if (is_array($data)) {
            $brick = array_merge($brick, $data);
        }

        return $this->template->fetch('bricks/' . $template, $brick);
    }


    /**
     * Получение id категории.
     */

    private function _getCategoryId($content = false)
    {
        if (is_array($content)) {
            if (isset($content['category'])) {
                return $content['category'];
            }

            return $content['id'];
        }

        return $this->_getCategoryIdFromCore();
    }


    /**
     * Получение id категории из данных ядра.
     */

    private function _getCategoryIdFromCore()
    {
        $coreData = $this->core->core_data;

        if ($coreData['data_type'] === 'category') {
            return $coreData['id'];
        }

        if ($coreData['data_type'] === 'page') {
            $page = get_page($coreData['id']);

            return $page['category'] ?: false;
        }

        return false;
    }


    /**
     * Получение id страницы.
     */

    private function _getPageId($content = false)
    {
        if (is_array($content)) {
            if (isset($content['category'])) {
                return $content['id'];
            }

            return false;
        }

        return $this->_getPageIdFromCore();
    }


    /**
     * Получение id страницы из данных ядра.
     */

    private function _getPageIdFromCore()
    {
        $coreData = $this->core->core_data;

        if ($coreData['data_type'] === 'page') {
            return $page['id'];
        }

        return false;
    }


    /**
     * Подписываемся на события админки.
     */

    public static function adminAutoload()
    {
        Events::create()->onAdminPagePreCreate()->setListener('_extendPageAdmin');
        Events::create()->onAdminPagePreEdit()->setListener('_extendPageAdmin');

        Events::create()->onAdminCategoryPreCreate()->setListener('_extendCategoryAdmin');
        Events::create()->onAdminCategoryPreUpdate()->setListener('_extendCategoryAdmin');

        Events::create()->onAdminCategoryCreate()->setListener('_onCategoryCreateOrUpdate');
        Events::create()->onAdminPageCreate()->setListener('_onPageCreateOrUpdate');

        Events::create()->onAdminCategoryUpdate()->setListener('_onCategoryCreateOrUpdate');
        Events::create()->onAdminPageUpdate()->setListener('_onPageCreateOrUpdate');

        Events::create()->onAdminCategoryDelete()->setListener('_onCategoryRemove');
        Events::create()->onAdminPageDelete()->setListener('_onPageRemove');
    }


    /**
     * "Дополнения модулей" для страницы.
     */

    public static function _extendPageAdmin($pageData = false)
    {
        self::_extendAdmin('page', $pageData ? $pageData['pageId'] : false);
    }


    /**
     * "Дополнения модулей" для категории.
     */

    public static function _extendCategoryAdmin($categoryData = false)
    {
        self::_extendAdmin('category', $categoryData ? $categoryData['pageId'] : false);
    }


    /**
     * "Дополнения модулей" общая логика.
     */

    private static function _extendAdmin($type, $contentId = false)
    {
        $self = self::_getInstance();

        $bricks = $self->BricksDataModel->getAllSortedByPosition();

        if (! $bricks) return;

        $data = [
            'type'   => $type,
            'bricks' => $self->_idToKey($bricks),
            'groups' => $self->_idToKey($self->BricksGroupModel->getAllSortedByPosition()),
        ];

        if ($contentId !== false) {
            $data['relations'] = $self->BricksRelationsModel->allByContent($contentId, $type);
        }

        $view = $self->_fetch('extend', $data);

        \CMSFactory\assetManager::create()
            ->registerScript('admin')
            ->registerStyle('admin')
            ->setData('moduleAdditions', $view);
    }


    /**
     * Реагирование на событие - создание или изменение категории.
     */

    public static function _onCategoryCreateOrUpdate($categoryData)
    {
        $ci = &get_instance();
        self::_updateRelations($categoryData['id'], 'category', $ci->input->post('bricks'));
    }


    /**
     * Реагирование на событие - создание или изменение страницы.
     */

    public static function _onPageCreateOrUpdate($pageData)
    {
        $ci = &get_instance();
        self::_updateRelations($pageData['id'], 'page', $ci->input->post('bricks'));
    }


    /**
     * Общая логика при создании или изменении контента.
     */

    private static function _updateRelations($itemId, $itemType, $bricks)
    {
        $self = self::_getInstance();
        $self->_restructuringFieldsArray($bricks);

        $self->BricksRelationsModel->makeRelations($itemId, $itemType, $bricks);
    }


    /**
     * Реагирование на событие - удаление категории.
     */

    public static function _onCategoryRemove($categoryData)
    {
        self::_removeRelations($categoryData['id'], 'category');
    }


    /**
     * Реагирование на событие - удаление страницы.
     */

    public static function _onPageRemove($pageData)
    {
        self::_removeRelations($pageData['id'], 'page');
    }


    /**
     * Общая логика при удалении контента.
     */

    public static function _removeRelations($itemId, $itemType)
    {
        $self = self::_getInstance();
        $self->BricksRelationsModel->removeByContent($itemId, $itemType);
    }


    /**
     * Получение экземпляра класса для выполнения нестатических методов.
     */

    private static function _getInstance()
    {
        $ci = &get_instance();
        $ci->load->module('bricks');

        return $ci->bricks;
    }


    /**
     * Рендер для вкладки "Дополнения модулей" контента.
     */

    private function _fetch($path, $data = null)
    {
        return $this->template->fetch('file:' . __DIR__ . '/assets/admin/' . $path, $data);
    }


    /**
     * Установка модуля
     */

    public function _install()
    {
        $this->_adminOr404();

        $this->load->model('BricksInstallModel')->install();
    }


    /**
     * Удаление модуля
     */

    public function _deinstall()
    {
        $this->_adminOr404();

        $this->load->model('BricksInstallModel')->uninstall();
    }


    /**
     * Проверка пользователя на админа.
     */

    private function _adminOr404()
    {
        if (! $this->dx_auth->is_admin()) {
            $this->core->error_404();
        }
    }
}