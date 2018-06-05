<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/23
 * Time: 15:31
 */

namespace app\admin\controller\resources;

use app\common\controller\Backend;

class Resources extends Backend
{
	/**
	 * WelcomeResource模型对象
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
				$v['fullurl'] = $cdnurl.$v['filepath'];
			}
			unset($v);

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
					//处理文件大小和类型
					$attachmentFile = ROOT_PATH . '/public' . $params['filepath'];
					if (is_file($attachmentFile))
					{
						$params['size'] = round(filesize($attachmentFile)/(1024*1024), 3);
						$finfo = mime_content_type($attachmentFile);
						if(is_numeric(stripos($finfo, 'video'))){
							$params['file_type'] = 'video';
						}elseif(is_numeric(stripos($finfo, 'image'))){
							$params['file_type'] = 'image';
						}else{
							$this->error(__('The type of file selected is incorrect'));
						}
					}else{
						$this->error(__('The selected file has been deleted'));
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

	/**
	 * 编辑
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
					// 判断是否修改图片
					if($params['filepath'] != $row['filepath']){
						$attachmentFile = ROOT_PATH . '/public' . $params['filepath'];
						if (is_file($attachmentFile))
						{
							$params['size'] = round(filesize($attachmentFile)/(1024*1024), 3);
							$finfo = mime_content_type($attachmentFile);
							if(is_numeric(stripos($finfo, 'video'))){
								$params['file_type'] = 'video';
							}elseif(is_numeric(stripos($finfo, 'image'))){
								$params['file_type'] = 'image';
							}else{
								$this->error(__('The type of file selected is incorrect'));
							}
						}else{
							$this->error(__('The selected file has been deleted'));
						}
						$params['audit_status'] = 0;
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
		$this->view->assign("row", $row);
		return $this->view->fetch();
	}
}