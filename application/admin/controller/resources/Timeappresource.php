<?php

namespace app\admin\controller\resources;

use app\common\controller\Backend;

/**
 * 定时APP资源管理
 *
 * @icon fa fa-circle-o
 */
class Timeappresource extends Backend
{
    
    /**
     * TimingAppResource模型对象
     */
    protected $model = null;

    /**
     * 是否开启数据限制
     * 支持auth/personal
     * 表示按权限判断/仅限个人
     * 默认为禁用,若启用请务必保证表中存在admin_id字段
     */
    protected $dataLimit = 'personal';

    /**
     * 数据限制字段
     */
    protected $dataLimitField = 'admin_id';

    /**
     * 数据限制开启时自动填充限制字段值
     */
    protected $dataLimitFieldAutoFill = true;

    /**
     * 是否开启Validate验证
     */
    protected $modelValidate = true;

    /**
     * 是否开启模型场景验证
     */
    protected $modelSceneValidate = true;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('TimingAppResource');

    }
    


}
