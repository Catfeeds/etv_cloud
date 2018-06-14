<?php

namespace app\admin\controller\devices;

use app\admin\model\Custom;
use app\admin\model\DeviceBasics;
use app\common\controller\Backend;
use think\Db;
use think\exception\PDOException;
use app\admin\controller\devices\Common;

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

	protected $modelValidate = true;

	protected $modelSceneValidate = true;

	protected $admin_id = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('DeviceBasics');

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

	        $Customlist_class = new Customlist();
	        $customid_list = $Customlist_class->custom_id_device($this->admin_id);
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

		$Customlist_class = new Customlist();
		$customid_list = $Customlist_class->custom_id_device($this->admin_id);
		if($customid_list != config('get all')){
			if(!in_array($row['custom_id'], $customid_list))
				$this->error(__('You have no permission'));
		}

		$wifi_info = Db::name('device_wifiset')->where('mac', 'eq', $row['mac'])->find();
		$this->view->assign("row", $wifi_info);
		return $this->view->fetch();
	}

	/**
	 * 批量设置
	 */
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
					// 数据处理
					$Common = new Common();
					$basics_mac = $Common->maclist_by_custom_mac($params['custom_id'] , $params['mac_ids']);
					if(false == $basics_mac)
						$this->error(__('Choose device'));

					// 查询需要更改的mac list
					$basics_mac = $query_basics->field('mac')->select();

					$maclist = array_column($basics_mac, 'mac');
					foreach($maclist as $key=>$value){
						$insert_data[$key]['mac'] = $value;
						$insert_data[$key]['wifi_ssid'] = $params['wifi_ssid'];
						$insert_data[$key]['wifi_passwd'] = $params['wifi_passwd'];
						$insert_data[$key]['wifi_psk_type'] = $params['wifi_psk_type'];
						$insert_data[$key]['wifi_hot_spot'] = $params['wifi_hot_spot'];
						$insert_data[$key]['status'] = $params['status'];
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

		return $this->view->fetch();
	}

}
