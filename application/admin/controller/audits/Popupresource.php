<?php

namespace app\admin\controller\audits;

use app\admin\controller\audits\Resources;

/**
 * 弹窗广告资源管理
 *
 * @icon fa fa-circle-o
 */
class Popupresource extends Resources
{
    
    /**
     * TestPopupResource模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('PopupResource');

    }

}
