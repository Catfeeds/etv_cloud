<?php

namespace app\admin\controller\audits;

use app\admin\controller\audits\Resources;

/**
 * 简易广告管理
 *
 * @icon fa fa-circle-o
 */
class Simpleadresource extends Resources
{
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('SimpleadResource');

    }

}
