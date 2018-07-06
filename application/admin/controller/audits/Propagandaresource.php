<?php

namespace app\admin\controller\audits;

use app\admin\controller\audits\Resources;

/**
 * 宣传轮播资源管理
 *
 * @icon fa fa-circle-o
 */
class Propagandaresource extends Resources
{
    
    /**
     * TestPropagandaResource模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('PropagandaResource');

    }

}
