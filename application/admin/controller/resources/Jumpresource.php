<?php

namespace app\admin\controller\resources;

use app\admin\controller\resources\Resources;

/**
 * 跳转资源资源
 *
 * @icon fa fa-circle-o
 */
class Jumpresource extends Resources
{
    
    /**
     * JumpResource模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('JumpResource');
    }
    

}
