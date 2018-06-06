<?php

namespace app\admin\controller\devices;

use app\admin\model\Custom;
use app\admin\model\DeviceBasics;
use app\common\controller\Backend;
use app\admin\controller\devices\Customlist;
use think\Db;
use think\exception\PDOException;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Wifiset extends Backend
{
    
    /**
     * DeviceWifiset模型对象
     */
    protected $model = null;

	// 登录账号绑定的客户ID列表
	protected $custom_ids = [0];

	protected $modelValidate = true;

	protected $modelSceneValidate = true;

	protected $admin_id = null;

	protected $noNeedLogin = ['get_tree_list'];
	protected $noNeedRight = ['get_tree_list'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('DeviceBasics');

	    $this->customlist_class = new Customlist;

	    $this->admin_id = $this->auth->id;
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
	        $this->relationSearch = true;
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

	        $customid_list = $this->customlist_class->custom_id_device($this->admin_id);
	        if(!is_array($customid_list) && $customid_list == config('get all')){
		        $where_customid = [];
	        }else{
		        $where_customid['zxt_device_basics.custom_id'] = ['in', $customid_list];
	        }

            $total = model('DeviceBasics')
                ->where($where)
	            ->where($where_customid)
	            ->with('custom')
	            ->with('wifiset')
                ->count();

	        $list = model('DeviceBasics')
                ->where($where)
	            ->where($where_customid)
	            ->with('custom')
	            ->with('wifiset')
	            ->order('id', 'desc')
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

	/**
	 * 编辑
	 */
	public function edit($ids = NULL)
	{
		$row = model('DeviceBasics')->get($ids);
		if (!$row)
			$this->error(__('No Results were found'));

		if ($this->request->isPost())
		{
			$params = $this->request->post("row/a");
			if ($params)
			{
				Db::startTrans();
				try
				{
					//是否采用模型验证
					if ($this->modelValidate)
					{
						$validate_result = $this->validate($params, 'DeviceWifiset');
						if(true !== $validate_result){
							$this->error($validate_result);
						}
					}
					$params['mac'] = $row['mac'];
					if('none' == $params['wifi_psk_type'])
						$params['wifi_passwd'] = '';

					//更新wifiset表信息
					Db::name('device_wifiset')->insert($params, $replace=true);

					//更新指令信息 包括最近一次指令和指令表对应的信息
					if($params['status'] == 'normal'){
						$data_control['wifi_set'] = $data_control['lately_order'] = 'wifi set';
						$data_control['wifi_set_time'] = time();
						Db::name('device_basics')->where('mac', 'eq', $row['mac'])->update($data_control);
					}
				}
				catch (\think\exception\PDOException $e)
				{
					Log::write($e->getMessage());
					Log::save();
					Db::rollback();
					$this->error(__('Operation failed'));
				}
				Db::commit();
				$this->success();
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}

		$customid_list = $this->customlist_class->custom_id_device($this->admin_id);
		if($customid_list != config('get all')){
			if(!in_array($row['custom_id'], $customid_list))
				$this->error(__('You have no permission'));
		}

		$wifi_info = Db::name('device_wifiset')->where('mac', 'eq', $row['mac'])->find();
		$this->view->assign("row", $wifi_info);
		return $this->view->fetch();
	}

	public function batch_set(){

		if($this->request->isPost()){
			$params = $this->request->post("row/a");
			if($params)
			{
				//数据验证
				if ($this->modelValidate)
				{
					$validate_result = $this->validate($params, 'DeviceWifiset');
					if(true !== $validate_result){
						$this->error($validate_result);
					}
				}

				Db::startTrans();
				try {
					//批量数据处理
					$maclist = []; //MAC集合
					$where_basics = [];
					// 选中设备的条件
					$query_basics = Db::name('device_basics');
					if(!empty($params['custom_id'])){
						$custom_id = DeviceBasics::handle_character(explode(",", $params['custom_id']));
						$where_basics['custom_id'] = ['in', $custom_id];
						$query_basics->where($where_basics);
					}
					if(!empty($params['mac_ids'])){
						$where_basics['mac'] = ['in', $params['mac_ids']];
						$query_basics->whereOr($where_basics);
					}
					if(empty($where_basics))
						$this->error(__('Choose device'));

					// 查询需要更改的mac list
					$basics_mac = $query_basics->field('mac')->select();

					if(!empty($basics_mac)){
						$maclist = array_column($basics_mac, 'mac');
						foreach($maclist as $key=>$value){
							$insert_data[$key]['mac'] = $value;
							$insert_data[$key]['wifi_ssid'] = $params['wifi_ssid'];
							$insert_data[$key]['wifi_passwd'] = $params['wifi_passwd'];
							$insert_data[$key]['wifi_psk_type'] = $params['wifi_psk_type'];
							$insert_data[$key]['wifi_hot_spot'] = $params['wifi_hot_spot'];
							$insert_data[$key]['status'] = $params['status'];
						}
					}else{
						$this->error(__('Invalid parameters'));
					}
					//更新wifiset表信息
					Db::name('device_wifiset')->insertAll($insert_data, $replace=true);

					//更新指令信息 包括最近一次指令和指令表对应的信息
					if($params['status'] == 'normal' && !empty($maclist)){
						$data_control['wifi_set'] = $data_control['lately_order'] = 'wifi set';
						$data_control['wifi_set_time'] = time();
						$where_control['mac'] = ['in', $maclist];
						Db::name('device_basics')->where($where_control)->update($data_control);
					}
				}catch (\think\exception\PDOException $e)
				{
					Log::write($e->getMessage());
					Log::save();
					Db::rollback();
					$this->error(__('Operation failed'));
				}
				Db::commit();
				$this->success();
			}else{
				$this->error(__('Parameter %s can not be empty', ''));
			}
		}

		$custom_list = $this->customlist_class->custom_list($this->admin_id); //获取客户列表
		if(empty($custom_list))
			$this->error(__('You have no permission'));
		return $this->view->fetch();
	}

	public function get_tree_list($id=null){
		if(empty($id)){
			$this->error(__('Unknown data format'));
		}
		$nodelist = [];
		if("#" == $id){
			$custom_list = $this->customlist_class->custom_list($this->admin_id);
			$nodelist = DeviceBasics::getCustomTreeList(array_column($custom_list, 'id'));
		}elseif(is_numeric(substr($id, 7))){
			$where_device['custom_id'] = substr($id, 7);
			$nodelist = DeviceBasics::getDeviceTreeList($where_device);
		}

		return json($nodelist);
	}



}
