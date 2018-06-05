<?php

namespace app\admin\controller\resources;

use app\admin\controller\resources\Resources;
use think\Db;

/**
 * 欢迎图片资源管理
 *
 * @icon fa fa-circle-o
 */
class Welcomeresource extends Resources
{
    
    /**
     * WelcomeResource模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('WelcomeResource');
    }

}
