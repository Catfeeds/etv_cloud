<?php

namespace app\admin\controller\logs;

use app\common\controller\Backend;

/**
 * 管理员日志管理
 *
 * @icon fa fa-circle-o
 */
class Adminlog extends Backend
{
    
    /**
     * Test_admin_log模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('AdminLog');
    }

}
