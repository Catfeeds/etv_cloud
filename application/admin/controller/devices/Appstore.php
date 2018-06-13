<?php

namespace app\admin\controller\devices;

use app\common\controller\Backend;
use think\Config;
use think\Db;
use think\exception\PDOException;
use app\admin\model\DeviceBasics;

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

	protected $noNeedRight = ['upload_apk', 'upload_icon', 'get_tree_list'];

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

	/**
	 * 删除
	 */
	public function del($ids = "")
	{
		if($ids){
			// 删除事件
			Hook::add('upload_delete', function($params) {
				$apk = ROOT_PATH . '/public/appstore_upload/' . $params['filepath'];
				$icon = ROOT_PATH . '/public/appstore_upload/' . $params['icon'];
				if (is_file($apk))
				{
					@unlink($apk);
				}
				if (is_file($icon))
				{
					@unlink($icon);
				}
			});

			$where_apk['id'] = $where_devices['app_id'] = ['in', $ids];
			$appstore_info = Db::name('appstore')->where($where_apk)->select();
			Db::startTrans();
			try{
				Db::name('appstore')->where($where_apk)->delete();
				Db::name('appstore_devices')->where($where_devices)->delete();
			}catch (PDOException $e){
				Db::rollback();
				$this->error(__('No rows were deleted'));
			}
			Db::commit();
			//监听删除
			foreach($appstore_info as $attachment){
				\think\Hook::listen("upload_delete", $attachment);
			}
			$this->success();
		}
		$this->error(__('Parameter %s can not be empty', 'ids'));
	}

	/**
	 * 分配至设备
	 */
	public function allot($ids = "") {

		if ($this->request->isPost())
		{
			$params = $this->request->post("row/a");

			if ($params)
			{
				//判断数据
				if(empty($params['custom_id']) && empty($params['mac_ids'])){
					$this->error(__('Parameter error'));
				}
				//整理客户列表ID
				if(!empty($params['custom_id'])){
					$params['custom_id'] = str_replace("custom_", "",$params['custom_id']);
				}
				//整理MAC列表及获取MAC对应的客户列表ID
				$device_list = [];
				if(!empty($params['mac_ids'])){
					$basics_list = Db::name('device_basics')->where('id', 'in', $params['mac_ids'])->field('id,custom_id')->select();
					foreach ($basics_list as $key=>$value){
						$device_list[$value['custom_id']][] = $value['id'];
					}
				}
				//更新数据
				$add_data = [];
				if(!empty($params['custom_id'])){
					$params_custom_id = explode(",", $params['custom_id']);
					foreach ($params_custom_id as $key=>$value){
						$add_data[$key]['app_id'] = $ids;
						$add_data[$key]['custom_id'] = $value;
						$add_data[$key]['mac_ids'] = 'all_mac';
					}
				}
				if(!empty($device_list)){
					$count = count($add_data);
					foreach ($device_list as $k=>$v){
						$add_data[$count+1]['app_id'] = $ids;
						$add_data[$count+1]['custom_id'] = $k;
						$add_data[$count+1]['mac_ids'] = implode(",", $v);
						$count++;
					}
				}
				//获取历史绑定数据
				$pass_data = Db::name('appstore_devices')->where('app_id','eq',$ids)->field('custom_id')->select();

				//需要删除数据
				$del_where = NULL;
				if(!empty($pass_data)){
					$del_data = array_diff(array_column($pass_data, 'custom_id'), array_column($add_data, 'custom_id'));
					if(!empty($del_data)){
						$del_where['custom_id'] = ['in', $del_data];
					}
				}

				Db::startTrans();
				try
				{
					Db::name('appstore_devices')->insertAll($add_data,$replace=true);
					if($del_where){
						Db::name('appstore_devices')->where($del_where)->delete();
					}
				}
				catch (\think\exception\PDOException $e)
				{
					Db::rollback();
					$this->error(__('Operation failed'));
				}
				Db::commit();
				$this->success();
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}

		$this->assignconfig('row_id', $ids);
		$this->view->assign('tips', __('Choose devices tips'));
		return $this->view->fetch();
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

	/**
	 * jstree调用方法
	 */
	public function get_tree_list($id = null, $row_id){
		if(empty($id)){
			$this->error(__('Unknown data format'));
		}
		$nodelist = [];

		if("#" == $id){
			$nodelist = self::getCustomTreeList($row_id);
		}elseif(is_numeric(substr($id, 7))){
			$where_appstore = []; //查询已选客户或设备的条件
			$where_device = [];   //查询设备基础表的条件
			$where_appstore['app_id'] = $row_id;
			$where_device['custom_id'] = $where_appstore['custom_id'] = substr($id, 7);
			$appstore_list = Db::name('appstore_devices')->where($where_appstore)->field('mac_ids')->find();
			$select = []; //已选MAC列表
			if(!empty($appstore_list) && 'all_mac'!=$appstore_list['mac_ids']){
				$select = explode(",", $appstore_list['mac_ids']);
			}
			$nodelist = DeviceBasics::getDeviceTreeList($where_device, $select);
		}
		return json($nodelist);
	}

	/**
	 * Jstree二次调用获取子节点方法
	 * @param $row_id APP操作列表ID
	 */
	private static function getCustomTreeList($row_id){
		$custom_list = collection(Db::name('custom')->field('id,custom_name')->select())->toArray();
		$appstore_devices_list = Db::name('appstore_devices')->where('app_id','eq', $row_id)->field('custom_id,mac_ids')->select();
		if(!empty($appstore_devices_list)){
			foreach ($appstore_devices_list as $key=>$value){
				if('all_mac' == $value['mac_ids']){
					$all_mac_custom_id[] = $value['custom_id'];  //mac为'all_mac'的客户ID
				}else{
					$open_custom_id[] = $value['custom_id'];     //mac为普通列表的客户ID
				}
			}
		}else{
			$all_mac_custom_id = [];
			$open_custom_id = [];
		}

		if (!empty($custom_list)){
			foreach ($custom_list as $key=>$value){
				$custom_list[$key]['id'] = 'custom_'. $value['id'];
				$custom_list[$key]['text'] = $value['custom_name'];
				$custom_list[$key]['children'] = true;
				if(in_array($value['id'], $all_mac_custom_id)){
					$state['selected'] = true;
				}
				if (in_array($value['id'], $open_custom_id)){
					$state['undetermined'] = true;
				}
				$custom_list[$key]['state'] = $state;
				$state = [];
			}
		}
		return $custom_list;
	}
}
