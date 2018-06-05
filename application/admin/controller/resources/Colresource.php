<?php

namespace app\admin\controller\resources;

use app\common\controller\Backend;
use think\db;
use think\Session;

/**
 * 栏目资源
 *
 * @icon fa fa-circle-o
 */
class Colresource extends Backend
{
    
    /**
     * ColResource模型对象
     */
    protected $model = null;

	protected $searchFields = 'title';

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
		$this->model = model('ColResource');

		// 资源对应的栏目信息
		if(strstr($this->request->pathinfo(), '/ids/')){
			$ids = $this->request->route();
			$column_data = db::name('column')->where('id='.$ids['ids'])->find();
			Session::set('column_data', $column_data, 'column_resource');
		}

	    // 资源类型
	    $resources_type = config('resources_type');
	    foreach($resources_type as $key => &$resources_value){
		    $resources_value = __($resources_value);
	    }
	    unset($resources_value);
	    array_unshift($resources_type, __('None'));
	    $this->view->assign('resources_type', $resources_type);
    }

	public function index()
	{
		$column_data = Session::get('column_data', 'column_resource');
		if(!in_array($column_data['level'], config('had_resource_level'))){
			$warming = __('Resources tips');
		}else{
			$warming = '';
		}

		// 添加获取列表的条件
		$where_other['column_pid'] = $column_data['id'];

		if ($this->request->isAjax())
		{
			list($where, $sort, $order, $offset, $limit) = $this->buildparams();
			$total = $this->model
				->where($where)
				->where($where_other)
				->order($sort, $order)
				->count();

			$list = $this->model
				->where($where)
				->where($where_other)
				->order($sort, $order)
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
		$this->view->assign('warming', $warming);
		return $this->view->fetch();
	}

	public function add()
	{
		if ($this->request->isPost())
		{
			$params = $this->request->post("row/a");
			if ($params)
			{
				// 参数处理
				$params['admin_id'] = $this->auth->id; //admin_id处理
				if(!empty($params['resource_type'])){
					switch($params['resource_type']){ //resource处理
						case 'video':
							$params['resource'] = $params['resource_video'];
							$attachmentFile = ROOT_PATH . '/public' . $params['resource'];
							if(is_file($attachmentFile)){
								$params['size'] = round(filesize($attachmentFile)/(1024*1024), 3);
							}else{
								$this->error(__('The selected file has been deleted'));
							}
							break;
						case 'image':
							$params['resource'] = $params['resource_image'];
							$attachmentFile = ROOT_PATH . '/public' . $params['resource'];
							if(is_file($attachmentFile)){
								$params['size'] = round(filesize($attachmentFile)/(1024*1024), 3);
							}else{
								$this->error(__('The selected file has been deleted'));
							}
							break;
						case  'url':
							$params['resource'] = $params['resource_url'];
							$params['size'] = 0;
							break;
						default :
							$params['resource'] = '';
					}

				}else{
					$this->error(__('Resource type empty'));
				}
				$column_data = Session::get('column_data', 'column_resource');
				$params['column_pid'] = $column_data['id'];
				$params['column_fpid'] = $column_data['fpid'];

				try
				{
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
						$this->success();
					}
					else
					{
						$this->error($this->model->getError());
					}
				}
				catch (\think\exception\PDOException $e)
				{
					$this->error(__('Operation failed'));
				}
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}
		return $this->view->fetch();
	}

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
					// 参数处理
					$params['audit_status'] = 0;
					if(!empty($params['resource_type'])){
						switch($params['resource_type']){ //resource处理
							case 'video':
								$params['resource'] = $params['resource_video'];
								$attachmentFile = ROOT_PATH . '/public' . $params['resource'];
								if(is_file($attachmentFile)){
									$params['size'] = round(filesize($attachmentFile)/(1024*1024), 3);
								}else{
									$this->error(__('The selected file has been deleted'));
								}
								break;
							case 'image':
								$params['resource'] = $params['resource_image'];
								$attachmentFile = ROOT_PATH . '/public' . $params['resource'];
								if(is_file($attachmentFile)){
									$params['size'] = round(filesize($attachmentFile)/(1024*1024), 3);
								}else{
									$this->error(__('The selected file has been deleted'));
								}
								break;
							case  'url':
								$params['resource'] = $params['resource_url'];
								$params['size'] = 0;
								break;
							default :
								$params['resource'] = '';
						}

					}else{
						$this->error(__('Resource type empty'));
					}
					//是否采用模型验证
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
					$this->error(__('Operation failed'));
				}
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}
		if($row['resource_type'] == 'video'){
			$row['resource_video'] = $row['resource'];
			$row['resource_image'] = '';
			$row['resource_url'] = '';
		}elseif($row['resource_type'] == 'image'){
			$row['resource_image'] = $row['resource'];
			$row['resource_video'] = '';
			$row['resource_url'] = '';
		}elseif($row['resource_type'] == 'url'){
			$row['resource_url'] = $row['resource'];
			$row['resource_image'] = '';
			$row['resource_video'] = '';
		}else{
			$row['resource_video'] = '';
			$row['resource_image'] = '';
			$row['resource_url'] = '';
		}
		$this->view->assign("row", $row);
		return $this->view->fetch();
	}
}
