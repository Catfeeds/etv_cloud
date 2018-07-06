<?php

namespace app\admin\controller\audits;

use app\admin\controller\audits\Resources;

/**
 * 跳转资源资源
 *
 * @icon fa fa-circle-o
 */
class Jumpresource extends Resources
{
    
    /**
     * TestJumpResource模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('JumpResource');

    }

}
