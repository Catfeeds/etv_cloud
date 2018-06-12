<?php

namespace app\admin\controller\devices;

use app\common\controller\Backend;
use think\Config;
use think\Db;
use think\exception\PDOException;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Appstore extends Backend
{
    
    /**
     * Appstore模型对象
     */
    protected $model = null;

	protected $modelValidate = true;

	protected $modelSceneValidate = true;

	protected $noNeedRight = ['upload_apk', 'upload_icon'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Appstore');

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
		    	if($v['icon']){
		    		$v['icon_url'] = $cdnurl.$v['icon'];
			    }else{
				    $v['icon_url'] = '';

			    }
			    $v['filepath_url'] = $cdnurl.$v['filepath'];
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
					$this->error($e->getMessage());
				}
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}

		//app类型
		$app_type_info = Config::get('app_type_info');
		foreach ($app_type_info as &$value){
			$value = __($value);
		}
		$this->view->assign('app_type_info', $app_type_info);

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
					//是否采用模型验证
					if ($this->modelValidate)
					{
						$name = basename(str_replace('\\', '/', get_class($this->model)));
						$validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
						$row->validate($validate);
					}
					$params['audit_status'] = 'unaudited';
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

		//app类型
		$app_type_info = Config::get('app_type_info');
		foreach ($app_type_info as &$value){
			$value = __($value);
		}
		$this->view->assign('app_type_info', $app_type_info);
		$this->view->assign("row", $row);
		return $this->view->fetch();
	}

	public function del($ids = "")
	{
		if($ids){
			$where_apk['id'] = $where_devices['app_id'] = ['in', $ids];
			Db::startTrans();
			try{
				Db::name('appstore')->where($where_apk)->delete();
				Db::name('appstore_devices')->where($where_devices)->delete();
			}catch (PDOException $e){
				Db::rollback();
				$this->error(__('No rows were deleted'));
			}
			Db::commit();
			$this->success();
		}
		$this->error(__('Parameter %s can not be empty', 'ids'));
	}

	/**
	 * 上传apk
	 */
	public function upload_apk(){

		Config::set('default_return_type', 'json');
		$file = $this->request->file('file');
		if (empty($file)) {
			$this->error(__('No file upload or server upload limit exceeded'));
		}

		$info = $file->validate(['ext'=>'apk'])->move(ROOT_PATH. '/public/uploads/appstore_upload');
		if($info){
			$this->success(__('Upload successful'), null, [
				'url'   => '/uploads/appstore_upload/' . $info->getSaveName(),
				'sha1'  => $info->hash()
			]);
		}else{
			// 上传失败获取错误信息
			$this->error($file->getError());
		}
	}

	/**
	 * 上传图标
	 */
	public function upload_icon(){

		Config::set('default_return_type', 'json');
		$file = $this->request->file('file');
		if (empty($file)) {
			$this->error(__('No file upload or server upload limit exceeded'));
		}

		$icon_type = Config::get('picture_type'); //图片类型
		$info = $file->validate(['ext'=>$icon_type])->move(ROOT_PATH. '/public/uploads/appstore_upload');
		if($info){
			$this->success(__('Upload successful'), null, [
				'url'   => '/uploads/appstore_upload/' . $info->getSaveName(),
				'sha1'  => $info->hash()
			]);
		}else{
			// 上传失败获取错误信息
			$this->error($file->getError());
		}
	}

}
