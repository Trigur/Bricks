<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

use TrigurPackage\Admin\AbstractAdminControllersLoader;

/*
    Велосипед загрузки классов-контроллеров админки.
*/

class Admin extends AbstractAdminControllersLoader
{
    protected static $moduleName = 'bricks';
    protected static $basePath = '/admin/components/init_window/bricks/data';

    protected function _dirname()
    {
        return __DIR__;
    }
}