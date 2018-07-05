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
		'check_params'      =>  ['only'=>'device_order,system_info,appstore_info,online_heart']
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
				$para_log_obj = Db::name('device_para_log')->where('mac','eq',$params['mac'])->order('runtime desc')->find();
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

				if(!empty($para_log_obj)){
					$para_data['before_info'] = $para_log_obj['after_info'];
				}else{
					$para_data['before_info'] = '';
				}
				$para_data['mac'] = $params['mac'];
				$para_data['runtime'] = time();
				$para_data['after_info'] = json_encode($params);
				$para_data['after_info'] = $this->para_to_string(array_merge($data_basics,$data_detail));
				Db::name('device_para_log')->insert($para_data);
			}catch (\Exception $e){
				Db::rollback();
				Log::write('设备信息上传出错,错误信息如下:'.$e->getMessage());
				Log::save();
				$this->error('设备信息上传出错',null,-5);
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

	/**
	 * 上传系统升级结果
	 * @param custom_id 客户编号
	 * @param mac Mac编号
	 * @param pass_utc 旧utc号
	 * @param current_utc 升级后utc号
	 * @param version 版本号
	 */
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
		$params['room'] = isset($params['room'])? $params['room']: '-';
		$params['message'] = isset($params['message'])? $params['message']: '-';
		$params['login_ip'] = $this->request->ip();
		$params['runtime'] = time();
		unset($params['mac']);

		try{
			Db::name('upgrade_system_log')->field('custom_id, mac_id, pass_utc, current_utc, version, room, message, runtime, login_ip')->insert($params);
		}catch (\Exception $e){
			Log::write("系统升级结果上报出错,错误信息如下:".$e->getMessage());
			Log::save();
			$this->error('上传指令结果出错', null, -5);
		}
		$this->success('Success', null,0);
	}

	/**
	 * 获取APPSTORE信息
	 * @param custom_id 客户编号
	 * @param mac MAC编号
	 */
	public function appstore_info() {
		$custom_id = $this->request->get('custom_id');
		$mac = $this->request->get('mac');
		$custom_obj = Db::name('custom')->where('custom_id','eq',$custom_id)->field('id')->find();
		$device_obj = Db::name('device_basics')->where('mac','eq',$mac)->field('id')->find();
		if(empty($custom_obj) || empty($device_obj)){
			$this->error(__('Invalid parameters'), null, -2);
		}
		$device_id = $device_obj['id'];
		// 获取绑定表APPid
		$appstore_devices_info = Db::name('appstore_devices')->where('custom_id','eq',$custom_obj['id'])
									->where("find_in_set($device_id, mac_ids) or mac_ids='all_mac'")
									->field('app_id')
									->select();

		//全部推送APP包括 绑定表APPid和push_all为true的APP
		if(empty($appstore_devices_info)){
			$where_and = "push_all = 'true'";
		}else{
			$app_device_ids =  implode(",", array_column($appstore_devices_info,'app_id'));
			$where_and = "id in ($app_device_ids) or push_all = 'true' ";
		}
		$where['status'] = 'normal';
		$where['audit_status'] = 'egis';
		$field = "id, type, name, version, package, filepath, sha1, icon";
		$appstore_info = Db::name('appstore')->where($where)->where($where_and)->field($field)->select();

		if(!empty($appstore_info)){
			//获取排序设置和安装情况并赋值
			$app_setting_obj = Db::name('device_app_setting')->where('id','in',$device_id)->find();
			if(!empty($app_setting_obj)){
				$weigh = !empty($app_setting_obj)? json_decode($app_setting_obj['weigh'], true): [];
				$install = !empty($app_setting_obj)? json_decode($app_setting_obj['install'], true): [];
				foreach ($appstore_info as $key=>&$value){
					$value['weigh'] = isset($weigh[$value['id']])? $weigh[$value['id']]: 100;
					$value['install'] = isset($install[$value['id']])? $install[$value['id']]: 'not installed';
				}
			}else{
				foreach ($appstore_info as $key=>&$value){
					$value['weigh'] = $key+1;
					$value['install'] = 'not installed';
				}
			}
			$this->success('Success',$appstore_info,0);
		}else{
			$this->success('Success',null,0);
		}
	}

	/**
	 * 上传AppStore安装结果
	 * @param mac MAC编号
	 * @param version APP版本号(唯一)
	 * @installed_result 安装结果
	 */
	public function appstore_installed_result(){
		$params = $this->request->post();
		if(!isset($params['mac']) || !isset($params['version']) || !isset($params['installed_result']))
			$this->error(__('Parameter error'), [], -1);

		$installed_result = ['not installed', 'installed', 'delete'];
		if(!in_array($params['installed_result'], $installed_result))
			$this->error(__('Parameter error'), [], -1);

		$device_obj = Db::name('device_basics')->where('mac','eq',$params['mac'])->field('id')->find();
		$appstore_obj = Db::name('appstore')->where('version','eq',$params['version'])->field('id')->find();
		if(empty($device_obj) || empty($appstore_obj))
			$this->error(__('Invalid parameters'), null, -2);

		$setting_obj = Db::name('device_app_setting')->where('id','eq',$device_obj['id'])->find();
		if (!empty($setting_obj) || !empty($setting_obj['install'])){
			$install = json_decode($setting_obj['install'],true);
			if(isset($install[$appstore_obj['id']])){
				$install[$appstore_obj['id']] = $params['installed_result'];
				$insert_data['install'] = json_encode($install);
			}else{
				$insert_data['install'] = json_encode([$appstore_obj['id']=>$params['installed_result']]);
			}
		}
		$insert_data['weigh'] = isset($setting_obj['weigh'])? $setting_obj['weigh']: '';
		$insert_data['id'] = $device_obj['id'];
		try{
			Db::name('device_app_setting')->insert($insert_data,true);
		}catch (\Exception $e){
			Log::write('App安装上传接口出错,错误信息如下:'.$e->getMessage());
			Log::save();
			$this->error('上传App安装结果出错',null,-5);
		}
		$this->success('Success',null,0);
	}

	/**
	 * 更新在线状态
	 * @param mac MAC编号
	 * @param custom_id 客户编号
	 */
	public function online_heart(){
		$mac = $this->request->get('mac');
		$device_obj = Db::name('device_basics')->where('mac','eq',$mac)->field('id')->find();
		if(!empty($device_obj)){
			$update_data['last_visit_time'] = time();
			$update_data['last_visit_ip'] = $this->request->ip();
			try{
				Db::name('device_basics')->where('id','eq',$device_obj['id'])->update($update_data);
			}catch (\Exception $e){
				Log::write("更新在线状态接口出错,错误信息如下:".$e->getMessage());
				Log::save();
				$this->error('更新在线状态出错',null,-5);
			}
			$this->success('Success',null,0);
		}
		$this->error(__('Invalid parameters'), null, -2);
	}

	/**
	 * 上传错误信息
	 */
	public function error_info() {
		$params = $this->request->post();
		if(!isset($params['mac']) || !isset($params['error_type']) || !isset($params['error_name']) || !isset($params['error_message'])){
			$this->error(__('Parameter error'), [], -1);
		}

		$device_obj = Db::name('device_basics')->where('mac','eq',$params['mac'])->field('id')->find();
		if(!empty($device_obj)){
			$field = 'mac,error_time,error_type,error_name,error_message,error_stack,agent,mode,referer';
			$params['error_time'] = time();
			try{
				Db::name('error_log')->field($field)->insert($params);
			}catch (\Exception $e){
				Log::write("上传错误信息接口出错,错误如下:".$e->getMessage());
				Log::save();
				$this->error('设备信息上传出错',null,-5);
			}
			$this->success('Success',null,0);
		}


	}

	private function para_to_string($stbinfo){

		$str = "";
		$str .="<p><strong>硬件信息</strong></p>";
		$str .="<p>MAC地址：".$stbinfo['mac']."</p>";
		$str .="<p>房间号：".$stbinfo['room']."</p>";
		$str .="<p>酒店编号：".$stbinfo['custom_id']."</p>";
		$str .="<p>软件版本：".$stbinfo['firmware_version']."</p>";

		$str .="<p>ITV版本：".$stbinfo['itv_version']."</p>";
		$str .="<p>路由固件版本：".$stbinfo['route_firmware_version']."</p>";

		$str .="<p>Model：".$stbinfo['model']."</p>";
		$str .="<p>Brand：".$stbinfo['brand']."</p>";
		$str .="<p>Board：".$stbinfo['board']."</p>";

		$str .="<p><strong>网络模式</strong></p>";
		$str .="<p>模式：".$stbinfo['network_mode']."</p>";
		$str .="<p>ITV模式：".$stbinfo['itv_mode']."</p>";
		$str .="<p>WAN模式：".$stbinfo['wan_mode']."</p>";

		$str .="<p>VLAN号：".$stbinfo['vlan_number']."</p>";
		$str .="<p>VLAN状态：".$stbinfo['vlan_status']."</p>";

		$str .="<p><strong>ITV参数</strong></p>";
		$str .="<p>itv_dhcp_user：".$stbinfo['itv_dhcp_user']."</p>";
		$str .="<p>itv_dhcp_pwd：".$stbinfo['itv_dhcp_pwd']."</p>";
		$str .="<p>itv_pppoe_user：".$stbinfo['itv_pppoe_user']."</p>";
		$str .="<p>itv_pppoe_pwd：".$stbinfo['itv_pppoe_pwd']."</p>";

		$str .="<p>itv_pppoe_ip：".$stbinfo['itv_pppoe_ip']."</p>";
		$str .="<p>itv_dhcp_ip：".$stbinfo['itv_dhcp_ip']."</p>";
		$str .="<p>itv_dhcp+_ip：".$stbinfo['itv_dhcp_plus_ip']."</p>";

		$str .="<p>itv_static_ip：".$stbinfo['itv_static_ip']."</p>";
		$str .="<p>itv_netmask：".$stbinfo['itv_netmask']."</p>";
		$str .="<p>itv_gateway：".$stbinfo['itv_gateway']."</p>";
		$str .="<p>itv_dns1：".$stbinfo['itv_dns1']."</p>";
		$str .="<p>itv_dns2：".$stbinfo['itv_dns2']."</p>";

		$str .="<p><strong>WAN参数</strong></p>";
		$str .="<p>wan_pppoe_user：".$stbinfo['wan_pppoe_user']."</p>";
		$str .="<p>wan_pppoe_pwd：".$stbinfo['wan_pppoe_pwd']."</p>";

		$str .="<p>wan_pppoe_ip：".$stbinfo['wan_pppoe_ip']."</p>";
		$str .="<p>wan_dhcp_ip：".$stbinfo['wan_dhcp_ip']."</p>";

		$str .="<p>wan_static_ip：".$stbinfo['wan_static_ip']."</p>";
		$str .="<p>wan_netmask：".$stbinfo['wan_netmask']."</p>";
		$str .="<p>wan_gateway：".$stbinfo['wan_gateway']."</p>";
		$str .="<p>wan_dns1：".$stbinfo['wan_dns1']."</p>";
		$str .="<p>wan_dns2：".$stbinfo['wan_dns2']."</p>";

		$str .="<p><strong>其他</strong></p>";
		$str .="<p>业务账号：".$stbinfo['aaa_account']."</p>";
		$str .="<p>业务密码：".$stbinfo['aaa_passwd']."</p>";
		$str .="<p>最近上线IP：".$stbinfo['last_visit_ip']."</p>";
		$str .="<p>最近上线时间：".date("Y-m-d H:i:s", $stbinfo['last_visit_time'])."</p>";

		return $str;
	}
}