<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/6
 * Time: 14:20
 */

namespace app\admin\controller\audits;

use app\common\controller\Backend;

class Resources extends Backend
{
	public function _initialize()
	{
		parent::_initialize();
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
				->count();

			$list = $this->model
				->where($where)
				->order('audit_status desc')
				->limit($offset, $limit)
				->select();

			$cdnurl = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root());
			foreach ($list as $k => &$v)
			{
				$v['fullurl'] = $cdnurl.$v['filepath'];
			}
			unset($v);

			$list = collection($list)->toArray();
			$result = array("total" => $total, "rows" => $list);

			return json($result);
		}
		return $this->view->fetch('audits/audit/index');
	}
}