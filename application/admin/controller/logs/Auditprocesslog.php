<?php

namespace app\admin\controller\logs;

use app\common\controller\Backend;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Auditprocesslog extends Backend
{
    
    /**
     * AuditProcessLog模型对象
     */
    protected $model = null;

	protected $relationSearch = true;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('AuditProcessLog');

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

			list($where, $sort, $order, $offset, $limit) = $this->buildparams();
			$total = $this->model
				->where($where)
				->with('admin')
				->order($sort, $order)
				->count();

			$list = $this->model
				->where($where)
				->with('admin')
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
