<?php

namespace app\admin\controller\contentset;

use app\common\controller\Backend;
use app\admin\controller\contentset\Customlist;
use think\Cache;

/**
 * 跳转设置
 *
 * @icon fa fa-circle-o
 */
class Jumpset extends Backend
{
    
    /**
     * JumpSetting模型对象
     */
    protected $model = null;

	protected $modelValidate = true;

	protected $modelSceneValidate = true;

	protected $admin_id = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('JumpSetting');

	    $this->admin_id = $this->auth->id;
    }

    /**
     * 查看
     */
    public function index()
    {
	    $this->relationSearch = true;
	    $this->searchFields = "custom.custom_id";
	    //设置过滤方法
	    $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
        	$Customlist = new Customlist();
	        $where_customid['zxt_jump_setting.custom_id'] = ['in', $Customlist->custom_id($this->admin_id)];

	        list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
	            ->where($where)
	            ->where($where_customid)
	            ->with("custom")
                ->order($sort, $order)
                ->count();

	        $list = $this->model
		        ->where($where)
		        ->where($where_customid)
		        ->with("custom")
		        ->order($sort, $order)
		        ->limit($offset, $limit)
		        ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

	/**
	 * 添加
	 */
	public function add()
	{
		if ($this->request->isPost())
		{
			$params = $this->request->post("row/a");
			if ($params)
			{
				try
				{
					// 判断客户列表是否存在
					$custom_key = Cache::get($this->admin_id.'-jump-customlist');
					if(!in_array($params['custom_id'], $custom_key)){
						$this->error(__('Parameter error'));
					}
					//是否采用模型验证
					if ($this->modelValidate)
					{
						$name = basename(str_replace('\\', '/', get_class($this->model)));
						$validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
						$this->model->validate($validate);
					}
					$result = $this->model->allowField(true)->save($params);
					if ($result !== false)
					{
						Cache::rm($this->auth->id.'-jump-customlist');
						$this->success();
					}
					else
					{
						$this->error($this->model->getError());
					}
				}
				catch (\think\exception\PDOException $e)
				{
					$this->error($e->getMessage());
				}
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}

		$Customlist_Class = new Customlist();
		$customlist = $Customlist_Class->custom_list($this->admin_id);
		Cache::set($this->admin_id.'-jump-customlist', array_column($customlist, 'id'), 36000); //设置10小时缓存,用于判断客户ID
		foreach($customlist as $cv){
			$custom_lists[$cv['id']] = $cv['custom_name'];//客户列表
		}

		$play_set_info = config('jump_play_set'); //跳转设置列表
		foreach($play_set_info as &$psv){
			$psv = __($psv);
		}

		$save_set_info = config('save_set'); //存储位置列表
		foreach($save_set_info as &$sav){
			$sav = __($sav);
		}

		$this->view->assign('custom_lists', $custom_lists);
		$this->view->assign('play_set_info', $play_set_info);
		$this->view->assign('save_at_info', $save_set_info);
		return $this->view->fetch();
	}

	/**
	 * 编辑
	 */
	public function edit($ids = NULL)
	{
		$row = $this->model->get($ids);
		if (!$row)
			$this->error(__('No Results were found'));

		if ($this->request->isPost())
		{
			$params = $this->request->post("row/a");
			if ($params)
			{
				try
				{
					//修改栏目的权限判断
					$Customlist_class = new Customlist();
					$custom_id_list = $Customlist_class->custom_id($this->admin_id);
					if(!in_array($row['custom_id'], $custom_id_list))
						$this->error(__('You have no permission'));

					if ($this->modelValidate)
					{
						$name = basename(str_replace('\\', '/', get_class($this->model)));
						$validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
						$row->validate($validate);
					}
					$result = $row->allowField(true)->save($params);
					if ($result !== false)
					{
						$this->success();
					}
					else
					{
						$this->error($row->getError());
					}
				}
				catch (\think\exception\PDOException $e)
				{
					$this->error($e->getMessage());
				}
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}

		$play_set_info = config('jump_play_set'); //跳转设置列表
		foreach($play_set_info as &$psv){
			$psv = __($psv);
		}

		$save_set_info = config('save_set'); //存储位置列表
		foreach($save_set_info as &$sav){
			$sav = __($sav);
		}

		$this->view->assign('play_set_info', $play_set_info);
		$this->view->assign('save_at_info', $save_set_info);
		$this->view->assign("row", $row);
		return $this->view->fetch();
	}

	/**
	 * 资源
	 */
	public function resources($custom_id = ""){
		if ($this->request->isAjax())
		{
			$this->request->filter(['strip_tags']);
			$filter = $this->request->get("filter", '');
			$filter_decode = json_decode($filter, true);
			$where['jc.custom_id'] = array('eq', $filter_decode['custom_id']);
			$total = model('jump_custom')
				->alias('jc')
				->join('zxt_jump_custom jr', 'jc.rid=jr.id')
				->where($where)
				->count();

			$list = model('jump_custom')
				->alias('jc')
				->join('zxt_jump_resource jr', 'jc.rid=jr.id')
				->where($where)
				->field('jc.id, jc.status,jc.audit_status, jr.title, jr.file_type, jr.filepath, jr.size')
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
		$this->assignconfig('custom_id', $custom_id);
		return $this->view->fetch();
	}

	/**
	 * 启用禁用资源
	 */
	public function multi_resource($ids = ""){
		$ids = $ids ? $ids : $this->request->param("ids");
		if ($ids)
		{
			if ($this->request->has('params'))
			{
				parse_str($this->request->post("params"), $values);
				$values = array_intersect_key($values, array_flip(is_array($this->multiFields) ? $this->multiFields : explode(',', $this->multiFields)));
				if ($values)
				{
					model('jump_custom')->where($this->model->getPk(), 'in', $ids);
					$count = model('jump_custom')->allowField(true)->isUpdate(true)->save($values);
					if ($count)
					{
						$this->success();
					}
					else
					{
						$this->error(__('No rows were updated'));
					}
				}
				else
				{
					$this->error(__('You have no permission'));
				}
			}
		}
		$this->error(__('Parameter %s can not be empty', 'ids'));
	}
}
