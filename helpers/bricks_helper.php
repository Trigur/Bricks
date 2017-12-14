<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

if (! function_exists('contentBricks')) {
    function contentBricks($content = null, $groupName = null, $data = null, $prefix = null, $suffix = null)
    {
        $ci = &get_instance();

        return $ci->bricks->_getContentBricks($content, $groupName, $data, $prefix, $suffix);
    }
}

if (! function_exists('categoryBricks')) {
    function categoryBricks($categoryId = null, $groupName = null, $data = null, $prefix = null, $suffix = null)
    {
        $ci = &get_instance();

        return $ci->bricks->_getCategoryBricks($categoryId, $groupName, $data, $prefix, $suffix);
    }
}

if (! function_exists('pageBricks')) {
    function pageBricks($pageId = null, $groupName = null, $data = null, $prefix = null, $suffix = null)
    {
        $ci = &get_instance();

        return $ci->bricks->_getPageBricks($pageId, $groupName, $data, $prefix, $suffix);
    }
}

if (! function_exists('getBrick')) {
    function getBrick($brickName, $data = null)
    {
        $ci = &get_instance();

        return $ci->bricks->_getBrickByName($brickName, $data);
    }
}