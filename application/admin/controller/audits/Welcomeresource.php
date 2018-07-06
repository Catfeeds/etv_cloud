<?php

namespace app\admin\controller\audits;

use app\admin\controller\audits\Resources;

/**
 * 欢迎图片资源管理
 *
 * @icon fa fa-circle-o
 */
class Welcomeresource extends Resources
{
    
    /**
     * WelcomeResourceAudit模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('WelcomeResource');
    }

    

}
