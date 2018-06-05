<?php

namespace app\admin\controller\resources;

use app\admin\controller\resources\Resources;

/**
 * 简易广告管理
 *
 * @icon fa fa-circle-o
 */
class Simpleadresource extends Resources
{
    
    /**
     * SimpleadResource模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('SimpleadResource');
    }


}
