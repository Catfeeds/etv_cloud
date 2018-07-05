<?php

namespace app\admin\controller\logs;

use app\common\controller\Backend;

/**
 * 升级管理日志
 *
 * @icon fa fa-circle-o
 */
class Upgradesystemlog extends Backend
{
    
    /**
     * UpgradeSystemLog模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('UpgradeSystemLog');

    }

	/**
	 * 查看
	 */
	public function index()
	{
		//设置过滤方法
		$this->request->filter(['strip_tags']);
		if ($this->request->isAjax())
		{
			$this->relationSearch = true;
			list($where, $sort, $order, $offset, $limit) = $this->buildparams();
			$total = $this->model
				->where($where)
				->with('custom,mac')
				->order($sort, $order)
				->count();

			$list = $this->model
				->where($where)
				->with('custom,mac')
				->order($sort, $order)
				->limit($offset, $limit)
				->select();

			$list = collection($list)->toArray();
			$result = array("total" => $total, "rows" => $list);

			return json($result);
		}
		return $this->view->fetch();
	}
    

}
