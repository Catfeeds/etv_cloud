<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/2
 * Time: 14:04
 */

namespace app\api\controller;
use app\common\controller\Api;
use think\Config;
use think\Db;
use think\Exception;
use think\Log;

class Device extends Api
{

	// 无需登录的接口,*表示全部
	protected $noNeedLogin = ['*'];

	protected $beforeActionList = [
		'check_params'      =>  ['only'=>'device_order,system_info']
	];

	/**
	 * 判断参数
	 */
	protected function check_params(){
		$custom_id = $this->request->get('custom_id');
		$mac = $this->request->get('mac');
		if(empty($custom_id) || empty($mac)){
			$this->error(__('Parameter error'), [], -1);
		}
	}

	/**
	 * 上传设备信息
	 * @param 设备信息 [mac,custom_id,room,model,firmware_version]必传
	 */
	public function device_info()
	{
		if ($this->request->isPost()){
			$params = $this->request->post();
			if(!isset($params['mac']) || !isset($params['custom_id']) || !isset($params['room']) || !isset($params['model']) || !isset($params['firmware_version'])){
				$this->error(__('Parameter error'), [], -1);
			}
			$Custom_obj = Db::name('custom')->where('custom_id','eq',$params['custom_id'])->field('id')->find();
			if(empty($Custom_obj)){
				$this->error(__('Parameter error'), [], -1);
			}

			// 基础信息
			$data_basics['mac'] = $params['mac'];
			$data_basics['custom_id'] = $Custom_obj['id'];
			$data_basics['room'] = $params['room'];
			$data_basics['model'] = $params['model'];
			$data_basics['firmware_version'] = $params['firmware_version'];
			$data_basics['last_visit_time'] = time();
			$data_basics['last_visit_ip'] = $this->request->ip();
			$data_basics['usage'] = 'official';
			$data_basics['status'] = 'normal';

			//详细信息
			$data_detail['mac'] = $params['mac'];
			$data_detail['aaa_account'] = isset($params['aaa_account'])? $params['aaa_account']: '';
			$data_detail['aaa_passwd'] = isset($params['aaa_passwd'])? $params['aaa_passwd']: '';
			$data_detail['brand'] = isset($params['brand'])? $params['brand']: '';
			$data_detail['board'] = isset($params['board'])? $params['board']: '';
			$data_detail['network_mode'] = isset($params['network_mode'])? $params['network_mode']: '';
			$data_detail['itv_mode'] = isset($params['itv_mode'])? $params['itv_mode']: '';
			$data_detail['wan_mode'] = isset($params['wan_mode'])? $params['wan_mode']: '';
			$data_detail['itv_dhcp_user'] = isset($params['itv_dhcp_user'])? $params['itv_dhcp_user']: '';
			$data_detail['itv_dhcp_pwd'] = isset($params['itv_dhcp_pwd'])? $params['itv_dhcp_pwd']: '';
			$data_detail['itv_pppoe_user'] = isset($params['itv_pppoe_user'])? $params['itv_pppoe_user']: '';
			$data_detail['itv_pppoe_pwd'] = isset($params['itv_pppoe_pwd'])? $params['itv_pppoe_pwd']: '';
			$data_detail['itv_static_ip'] = isset($params['itv_static_ip'])? $params['itv_static_ip']: '';
			$data_detail['itv_netmask'] = isset($params['itv_netmask'])? $params['itv_netmask']: '';
			$data_detail['itv_gateway'] = isset($params['itv_gateway'])? $params['itv_gateway']: '';
			$data_detail['itv_dns1'] = isset($params['itv_dns1'])? $params['itv_dns1']: '';
			$data_detail['itv_dns2'] = isset($params['itv_dns2'])? $params['itv_dns2']: '';
			$data_detail['wan_pppoe_user'] = isset($params['wan_pppoe_user'])? $params['wan_pppoe_user']: '';
			$data_detail['wan_pppoe_pwd'] = isset($params['wan_pppoe_pwd'])? $params['wan_pppoe_pwd']: '';
			$data_detail['wan_static_ip'] = isset($params['wan_static_ip'])? $params['wan_static_ip']: '';
			$data_detail['wan_netmask'] = isset($params['wan_netmask'])? $params['wan_netmask']: '';
			$data_detail['wan_gateway'] = isset($params['wan_gateway'])? $params['wan_gateway']: '';
			$data_detail['wan_dns1'] = isset($params['wan_dns1'])? $params['wan_dns1']: '';
			$data_detail['wan_dns2'] = isset($params['wan_dns2'])? $params['wan_dns2']: '';
			$data_detail['itv_version'] = isset($params['itv_version'])? $params['itv_version']: '';
			$data_detail['route_firmware_version'] = isset($params['route_firmware_version'])? $params['route_firmware_version']: '';
			$data_detail['wan_pppoe_ip'] = isset($params['wan_pppoe_ip'])? $params['wan_pppoe_ip']: '';
			$data_detail['wan_dhcp_ip'] = isset($params['wan_dhcp_ip'])? $params['wan_dhcp_ip']: '';
			$data_detail['vlan_number'] = isset($params['vlan_number'])? $params['vlan_number']: '';
			$data_detail['vlan_status'] = isset($params['vlan_status'])? $params['vlan_status']: '';
			$data_detail['itv_pppoe_ip'] = isset($params['itv_pppoe_ip'])? $params['itv_pppoe_ip']: '';
			$data_detail['itv_dhcp_ip'] = isset($params['itv_dhcp_ip'])? $params['itv_dhcp_ip']: '';
			$data_detail['itv_dhcp_plus_ip'] = isset($params['itv_dhcp_plus_ip'])? $params['itv_dhcp_plus_ip']: '';
			$data_detail['boot_time'] = time();

			Db::startTrans();
			try{
				$basics_obj = Db::name('device_basics')->where('mac', 'eq', $params['mac'])->find();
				$detail_obj = Db::name('device_detail')->where('mac','eq',$params['mac'])->find();
				if(!empty($basics_obj)){
					Db::name('device_basics')->where('mac','eq',$params['mac'])->update($data_basics);
				}else{
					Db::name('device_basics')->insert($data_basics);
				}

				if (!empty($detail_obj)){
					Db::name('device_detail')->update($data_detail);
				}else{
					Db::name('device_detail')->insert($data_detail);
				}
			}catch (\Exception $e){
				Db::rollback();
				Log::write('设备信息上传出错,错误信息如下:'.$e->getMessage());
				Log::save();
				$this->error('',null,-5);
			}
			Db::commit();
			$this->success('Success',null,0);
		}
	}

	/**
	 * 获取设备设置指令
	 */
	public function device_order(){
		$mac = $this->request->get('mac');
		$where['mac'] = $mac;
		$where['status'] = 'normal';
		$cache_time = Config::get('api_cache_time');
		$device_order_cache_time = isset($cache_time['device_order'])? $cache_time['device_order']: 10;
		$basics_obj = Db::name('device_basics')
			->where('mac','eq',$mac)
			->cache($device_order_cache_time)
			->field('id,mac,reboot_set,clean_set,wifi_set,sleep_set')
			->find();
		if(!empty($basics_obj)){
			$return_data['mac'] = $basics_obj['mac'];
			$return_data['reboot_set'] = $basics_obj['reboot_set'];
			$return_data['clean_set'] = $basics_obj['clean_set'];
			if('wifi set' == $basics_obj['wifi_set']){
				$wifiset_obj = Db::name('device_wifiset')->where('mac','eq',$basics_obj['mac'])->field('id,wifi_ssid,wifi_passwd,wifi_psk_type,wifi_hot_spot,status')->select();
				$return_data['wifi_set'] = collection($wifiset_obj)->toArray();
			}else{
				$return_data['wifi_set'] = [];
			}

			if('sleep set' == $basics_obj['sleep_set']){
				$sleepset_obj = Db::name('device_sleep')->where('mac','eq',$basics_obj['mac'])->field('id,sleep_time_start,sleep_time_end,sleep_marked_word,sleep_countdown_time,sleep_image,status')->select();
				if(!empty($sleepset_obj)){
					foreach ($sleepset_obj as $key=>$value){
						$sleepset_obj[$key]['sleep_image'] = '/public/uploads/sleep_image/'.$value['sleep_image'].'.jpg';
					}
				}
				$return_data['sleep_set'] = collection($sleepset_obj)->toArray();
			}else{
				$return_data['sleep_set'] = [];
			}
			$this->success('Success', $return_data, 0);
		}
		$this->error(__('Invalid parameters'), null, -2);
	}

	/**
	 * 上传设备指令结果
	 */
	public function order_result(){
		$params = $this->request->post();
		if(!isset($params['mac']) || empty($params['mac']))
			$this->error(__('Parameter error'), [], -1);
		$update_data = []; //更新指令数据
		if(isset($params['reboot_result']) || !empty($params['reboot_result'])){
			$update_data['reboot_result'] = $params['reboot_result'];
			$update_data['reboot_result_time'] = time();
		}
		if(isset($params['clean_result']) || !empty($params['clean_result'])){
			$update_data['clean_result'] = $params['clean_result'];
			$update_data['clean_result_time'] = time();
		}
		if(isset($params['wifi_result']) || !empty($params['wifi_result'])){
			$update_data['wifi_result'] = $params['wifi_result'];
			$update_data['wifi_result_time'] = time();
		}
		if(isset($params['sleep_result']) || !empty($params['sleep_result'])){
			$update_data['sleep_result'] = $params['sleep_result'];
		}
		if(empty($update_data)){
			$this->error(__('Parameter error'), null, -1);
		}

		try{
			Db::name('device_basics')->where('mac','eq',$params['mac'])->update($update_data);
		}catch (\Exception $e){
			Log::write('API接口order_result,写入出错,错误信息如下:'.$e->getMessage());
			Log::save();
			$this->error('上传指令结果出错', null, -5);
		}
		$this->success('Success',null,0);
	}

	/**
	 * 获取系统升级信息
	 * @param custom_id 客户编号
	 * @param mac MAC
	 * @param current_utc 正使用的utc
	 * 判断utc 不等于返回
	 */
	public function system_info(){
		$current_version = $this->request->get('current_utc');
		if(empty($current_version) || !is_numeric($current_version))
			$this->error(__('Parameter error'), [], -1);

		$mac = $this->request->get('mac');
		$custom_id = $this->request->get('custom_id');
		$device_obj = Db::name('device_basics')->where('mac','eq',$mac)->field('id,status')->find();
		$custom_obj = Db::name('custom')->where('custom_id','eq',$custom_id)->field('id,status')->find();

		if (empty($device_obj) || empty($custom_obj))
			$this->error(__('Invalid parameters'), null, -2);

		if('hidden'==$device_obj['status'] || 'hidden'==$custom_obj['status'])
			$this->error(__('Parameter hidden'),null,-4);

		$device_id = $device_obj['id'];
		$bind_info = Db::name('upgrade_system_devices')
				->where('custom_id','eq',$custom_obj['id'])
				->where("find_in_set($device_id, mac_ids) or mac_ids = 'all_mac'")
				->field('sys_id')
				->select();
		$sys_ids = array_column($bind_info, 'sys_id');
		if(empty($sys_ids))
			$this->success('Success',null,0);

		$where_system['id'] = ['in', $sys_ids];
		$where_system['status'] = 'normal';
		$where_system['audit_status'] = 'egis';
		$where_system['utc'] = ['neq',$current_version];
		$field = 'id,utc,version,size,name,filepath,sha1';
		$system_obj = Db::name('upgrade_system')->where($where_system)->field($field)->order('utc desc')->limit(0,1)->select();
		$this->success('Success', $system_obj,0);
	}

	public function system_upgrade_result(){
		$params = $this->request->post();
		if(!isset($params['custom_id']) || !isset($params['mac']) || !isset($params['pass_utc']) || !isset($params['current_utc']) || !isset($params['version'])){
			$this->error(__('Parameter error'), [], -1);
		}
		$device_obj = Db::name('device_basics')->where('mac','eq',$params['mac'])->field('id')->find();
		$custom_obj = Db::name('custom')->where('custom_id','eq',$params['custom_id'])->field('id')->find();

		if (empty($device_obj) || empty($custom_obj))
			$this->error(__('Invalid parameters'), null, -2);

		$params['custom_id'] = $custom_obj['id'];
		$params['mac_id'] = $device_obj['id'];
		$params['login_ip'] = $this->request->ip();
		$params['runtime'] = time();
		unset($params['mac']);

		try{
			Db::name('upgrade_system_log')->strict(true)->insert($params);
		}catch (\Exception $e){
			Log::write("系统升级结果上报出错,错误信息如下:".$e->getMessage());
			Log::save();
			$this->error('上传指令结果出错', null, -5);
		}
		$this->success('Success', null,0);
	}
}