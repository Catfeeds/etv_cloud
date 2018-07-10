<?php

namespace app\admin\controller\audits;

use app\common\controller\Backend;

/**
 * 系统管理
 *
 * @icon fa fa-circle-o
 */
class Upgradesystem extends Backend
{
    
    /**
     * TestUpgradeSystem模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('UpgradeSystem');

    }

	public function index()
	{
		//设置过滤方法
		$this->request->filter(['strip_tags']);
		if ($this->request->isAjax())
		{
			//如果发送的来源是Selectpage，则转发到Selectpage
			if ($this->request->request('keyField'))
			{
				return $this->selectpage();
			}
			list($where, $sort, $order, $offset, $limit) = $this->buildparams();
			$total = $this->model
				->where($where)
				->order($sort, $order)
				->count();

			$list = $this->model
				->where($where)
				->order($sort, $order)
				->limit($offset, $limit)
				->select();

			$cdnurl = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root());
			foreach ($list as $k => &$v)
			{
				if($v['filepath']){
					$v['filepath_url'] = $cdnurl.$v['filepath'];
				}else{
					$v['filepath_url'] = '';

				}
				$v['filepath_url'] = $cdnurl.$v['filepath'];
			}
			unset($v);

			$list = collection($list)->toArray();
			$result = array("total" => $total, "rows" => $list);

			return json($result);
		}
		return $this->view->fetch('audits/audit/index');
	}
    

}
