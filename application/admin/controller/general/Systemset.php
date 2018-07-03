<?php

namespace app\admin\controller\general;

use app\common\controller\Backend;
use think\Config;
use think\exception\PDOException;
use think\Db;
use app\admin\model\DeviceBasics;

/**
 * 系统管理
 *
 * @icon fa fa-circle-o
 */
class Systemset extends Backend
{
    
    /**
     * UpgradeSystem模型对象
     */
    protected $model = null;

	protected $modelValidate = true;

	protected $modelSceneValidate = true;

	protected $noNeedRight = ['upload', 'get_tree_list'];

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
				    $params['createtime'] = time();
				    $params['updatetime'] = time();
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
			    catch (PDOException $e)
			    {
				    $this->error($e->getMessage());
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
		$this->view->assign("row", $row);
		return $this->view->fetch();
	}

	/**
	 * 删除
	 */
	public function del($ids = "")
	{
		if($ids){

			$where_sys['id'] = $where_devices['sys_id'] = ['in', $ids];
			$system_info = Db::name('upgrade_system')->where($where_sys)->select();

			Db::startTrans();
			try{
				Db::name('upgrade_system')->where($where_sys)->delete();
				Db::name('upgrade_system_devices')->where($where_devices)->delete();
			}catch (PDOException $e){
				Db::rollback();
				$this->error(__('No rows were deleted'));
			}
			Db::commit();
			//删除原件
			foreach($system_info as $attachment){
				$filepath = ROOT_PATH . '/public/' . $attachment['filepath'];
				if (is_file($filepath))
				{
					@unlink($filepath);
				}
			}
			$this->success();
		}
		$this->error(__('Parameter %s can not be empty', 'ids'));
	}

	/**
	 * 分配
	 */
	public function allot($ids = NULL){

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
						$add_data[$key]['sys_id'] = $ids;
						$add_data[$key]['custom_id'] = $value;
						$add_data[$key]['mac_ids'] = 'all_mac';
					}
				}
				if(!empty($device_list)){
					$count = count($add_data);
					foreach ($device_list as $k=>$v){
						$add_data[$count+1]['sys_id'] = $ids;
						$add_data[$count+1]['custom_id'] = $k;
						$add_data[$count+1]['mac_ids'] = implode(",", $v);
						$count++;
					}
				}
				//获取历史绑定数据
				$pass_data = Db::name('upgrade_system_devices')->where('sys_id','eq',$ids)->field('custom_id')->select();

				//需要删除数据
				$del_where = NULL;
				if(!empty($pass_data)){
					$del_data = array_values(array_diff(array_column($pass_data, 'custom_id'), array_column($add_data, 'custom_id')));
					if(!empty($del_data)){
						$del_where['custom_id'] = ['in', $del_data];
					}
				}

				Db::startTrans();
				try
				{
					Db::name('upgrade_system_devices')->insertAll($add_data,$replace=true);
					if($del_where){
						Db::name('upgrade_system_devices')->where($del_where)->delete();
					}
				}
				catch (PDOException $e)
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
			$where_appstore['sys_id'] = $row_id;
			$where_device['custom_id'] = $where_appstore['custom_id'] = substr($id, 7);
			$appstore_list = Db::name('upgrade_system_devices')->where($where_appstore)->field('mac_ids')->find();
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
	 */
	private static function getCustomTreeList($row_id){
		$custom_list = Db::name('custom')->field('id,custom_name')->select();
		$upgrade_devices_list = Db::name('upgrade_system_devices')->where('sys_id','eq', $row_id)->field('custom_id,mac_ids')->select();
		if(!empty($upgrade_devices_list)){
			foreach ($upgrade_devices_list as $key=>$value){
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

		//组合返回树状结
		$return_data = [];
		if (!empty($custom_list)){
			foreach ($custom_list as $key=>$value){
				$return_data[$key]['id'] = 'custom_'. $value['id'];
				$return_data[$key]['text'] = $value['custom_name'];
				$return_data[$key]['children'] = true;
				$state = [];
				if(!empty($all_mac_custom_id)){
					if(in_array($value['id'], $all_mac_custom_id)){
						$state['selected'] = true;
					}
				}
				if(!empty($open_custom_id)){
					if (in_array($value['id'], $open_custom_id)){
						$state['undetermined'] = true;
						$state['opened'] = true;
					}
				}
				$return_data[$key]['state'] = $state;
			}
		}
		return $return_data;
	}

	/**
	 * 上传
	 */
	public function upload(){

	    Config::set('default_return_type', 'json');
	    $file = $this->request->file('file');
	    if (empty($file)) {
		    $this->error(__('No file upload or server upload limit exceeded'));
	    }

	    $info = $file->validate(['ext'=>'zip'])->move(ROOT_PATH. '/public/uploads/system_upload');
	    if($info){
	    	//获取系统信息
		    $filepath = ROOT_PATH.'/public/uploads/system_upload/'.$info->getSaveName();
		    $zip = zip_open($filepath);
		    if ($zip) {
			    do {
				    $entry = zip_read($zip);
			    }while ($entry && zip_entry_name($entry) != "system/build.prop");
			    zip_entry_open($zip, $entry, "r");
			    $entry_content = zip_entry_read($entry, zip_entry_filesize($entry));

			    $version_release_last = strstr($entry_content, "ro.build.version.release=");
			    $version_release_arr = explode("<br />", nl2br($version_release_last));
			    $version_arr = explode("=", $version_release_arr['0']);
			    $version = $version_arr['1'];

			    $utc_open_pos = strpos($entry_content, "ro.build.date.utc=");
			    $utc_close_pos = strpos($entry_content, "ro.build.type", $utc_open_pos);
			    $utc = substr($entry_content,$utc_open_pos + strlen("ro.build.date.utc="),$utc_close_pos - ($utc_open_pos + strlen("ro.build.date.utc=")) );

			    zip_entry_close($entry);
			    zip_close($zip);


			    $this->success(__('Upload successful'), null, [
				    'url'       =>  '/uploads/system_upload/' . $info->getSaveName(),
				    'sha1'      =>  $info->hash(),
				    'version'   =>  $version,
				    'utc'       =>  $utc,
				    'size'      =>  $info->getSize()
			    ]);
		    }else{
//			    if (is_file($filepath))
//			    {
//				    @unlink($filepath);
//			    }
				// 上传失败获取错误信息
			    $this->error(__(''));
		    }


	    }else{
		    // 上传失败获取错误信息
		    $this->error($file->getError());
	    }
    }
    

}
