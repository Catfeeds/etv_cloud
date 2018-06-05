<?php

namespace app\admin\controller\resources;

use app\admin\controller\resources\Resources;

/**
 * 弹窗广告资源管理
 *
 * @icon fa fa-circle-o
 */
class Popupresource extends Resources
{
    
    /**
     * PopupResource模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('PopupResource');
    }

    

}
