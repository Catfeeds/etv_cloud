<?php

namespace app\admin\controller\resources;

use app\common\controller\Backend;
use fast\Tree;
use think\Db;
use think\Exception;
use think\Log;

/**
 * 栏目管理
 *
 * @icon fa fa-circle-o
 */
class Column extends Backend
{
    
    /**
     * Column模型对象
     */
    protected $model = null;
	protected $categorylist = [];

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
        $this->model = model('Column');
	    $this->request->filter(['strip_tags']);

	    // 判断权限
	    $admin_id = $this->auth->id;
	    $where_config['name'] = 'resource_column';
	    $config_column = Db::name('config')->where($where_config)->field('value')->find();
	    $column_admin = json_decode($config_column['value'], true); // json_decode获取特殊账号及其对应查看的账号列表值
	    $admin_key = array_unique(array_keys($column_admin)); //获取特殊账号列表
	    if(in_array($admin_id, $admin_key)){ //判断是否特殊账号
		    $where_admin['admin_id'] = array('in', $column_admin[$admin_id]);
	    }else{ // 普通的栏目主账号
		    $where_admin['admin_id'] = $admin_id;
	    }

	    // 树状处理
	    $tree = Tree::instance();
	    $list = collection($this->model->order('id desc')->where($where_admin)->select())->toArray();
        $tree->init($list, 'pid');
	    $this->categorylist = $tree->getTreeList($tree->getTreeArray(0), 'title');

	    $category_data = ['0' => __('None')];
	    foreach($this->categorylist as $k => $v)
	    {
		    if($v['level'] > config('lowest_haschild_level')){
			    continue;
		    }
		    $category_data[$v['id']] = $v['title'];
	    }
	    unset($v);

	    $this->view->assign('parentList', $category_data);

	    // 语言管理
	    $language_type = config('language_type');
	    foreach($language_type as $lang_key => &$lang_val)
	    {
		    $lang_val = __($lang_val);
	    }
	    unset($lang_val);
	    $this->view->assign('language_type', $language_type);
    }

	public function index()
	{
		if($this->request->isAjax())
		{
			$cdnurl = preg_replace("/\/(\w+)\.php$/i", '', $this->request->root());
			foreach($this->categorylist as $k=>&$v){
				if(empty($v['filepath'])){
					$v['fullurl'] = '';
				}else{
					$v['fullurl'] = $cdnurl.$v['filepath'];
				}
			}
			unset($v);
			$list = $this->categorylist;
			$total = count($this->categorylist);

			$result = array("total" => $total, "rows" => $list);
			return json($result);
		}
		return $this->view->fetch();
	}

	/**
	 * 判断栏目层级合法性
	 * 获取栏目的一级栏目id和栏目层级
	 * @param array $params
	 * @return array
	 */
	private function process_level_params($params=array())
	{
		if(!isset($params['pid'])){
			$return = [
				'code'  => -1,
				'msg'   => 'Parameter error'
			];
			return $return;
		}
		$parent_params = $this->model->where('id='.$params['pid'])->find();

		if(empty($parent_params)){
			$return = [
				'code'  => -1,
				'msg'   => 'The parent does not exist',
			];
			return $return;
		}

		if($parent_params['level']>config('lowest_haschild_level')){
			$return = [
				'code'  => -1,
				'msg'   => 'More than the lowest level',
			];
			return $return;
		}
		if($parent_params['language_type'] != $params['language_type']){
			$return = [
				'code'  => -1,
				'msg'   => 'Select the same language as the superior'
			];
			return $return;
		}
		if($parent_params['level'] == 1){
			$params['fpid'] = $parent_params['id'];
		}else{
			$params['fpid'] = $parent_params['fpid'];
		}
		$params['level'] = intval($parent_params['level']) + 1;
		$return = [
			'code'  => 0,
			'data'   => $params
		];
		return $return;
	}

	/**
	 * 检查语言类型问题
	 */
	private function check_language_type($pid, $language_type){
		$parent_params = $this->model->where('id='.$pid)->find();
		if($parent_params['language_type'] != $language_type){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * 添加
	 */
	public function add()
	{
		if($this->request->isPost())
		{
			$params = $this->request->post("row/a");

			if($params){
				try
				{
					if ($this->dataLimit && $this->dataLimitFieldAutoFill)
					{
						$params[$this->dataLimitField] = $this->auth->id;
					}

					//是否采用模型验证
					if ($this->modelValidate)
					{
						$name = basename(str_replace('\\', '/', get_class($this->model)));
						$validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
						$this->model->validate($validate);
					}

					// 完善层级数据
					if($params['pid'] !=0){
						$process_return = $this->process_level_params($params);
						if($process_return['code'] == -1){
							$this->error(__($process_return['msg']));
						}
						$params = $process_return['data'];
					}else{
						$params['fpid'] = 0;
						$params['level'] = 1;
					}

					$result = $this->model->allowField(true)->save($params);
					$add_id = $this->model->id;
					if ($add_id !== false)
					{
						$this->model->where('id', 'eq', $add_id)->setField('fpid',$add_id);
						$this->success($result);
					}
					else
					{
						$this->error($this->model->getError());
					}
				}catch(Exception $e){
					$this->error(__('Operation failed'));
				}
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}
		return $this->view->fetch();
	}

	/**
	 * 修改
	 */
	public function edit($ids = NULL)
	{
		$row = $this->model->get($ids);
		if (!$row)
			$this->error(__('No Results were found'));
		$adminIds = $this->getDataLimitAdminIds();
		if (is_array($adminIds))
		{
			if (!in_array($row[$this->dataLimitField], $adminIds))
			{
				$this->error(__('You have no permission'));
			}
		}
		if ($this->request->isPost())
		{
			$params = $this->request->post("row/a");
			if ($params)
			{
				try
				{
					//是否采用模型验证
					if ($this->modelValidate)
					{
						$name = basename(str_replace('\\', '/', get_class($this->model)));
						$validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
						$row->validate($validate);
					}
					if($row['pid'] >0){
						if(!$this->check_language_type($row['pid'], $params['language_type']) ){
							$this->error(__('Select the same language as the superior'));
						}
					}
					$result = $row->allowField(true)->save($params);
					if ($result !== false)
					{
						$this->success(__('Operation completed'));
					}
					else
					{
						$this->error($row->getError());
					}
				}
				catch (Exception $e)
				{
					$this->error(__('Operation failed'));
				}
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}
		$this->view->assign("row", $row);
		return $this->view->fetch();
	}
}
