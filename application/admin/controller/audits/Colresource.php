<?php

namespace app\admin\controller\audits;

use app\common\controller\Backend;

/**
 * 栏目资源
 *
 * @icon fa fa-circle-o
 */
class Colresource extends Backend
{
    
    /**
     * TestColResource模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ColResource');

    }

	public function index()
	{
		//设置过滤方法
		$this->request->filter(['strip_tags']);
		if ($this->request->isAjax())
		{
			list($where, $sort, $order, $offset, $limit) = $this->buildparams();
			$total = $this->model
				->where($where)
				->where('resource_type','neq','url')
				->count();

			$list = $this->model
				->where($where)
				->where('resource_type','neq','url')
				->order('audit_status desc')
				->limit($offset, $limit)
				->select();

			$cdnurl = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root());
			foreach ($list as $k => &$v)
			{
				if($v['resource_type'] == 'image' || $v['resource_type'] == 'video'){
					$v['fullurl'] = $cdnurl.$v['resource'];
				}else{
					$v['fullurl'] = '';
				}
			}
			unset($v);

			$list = collection($list)->toArray();
			$result = array("total" => $total, "rows" => $list);

			return json($result);
		}
		return $this->view->fetch('audits/audit/index');
	}


}
