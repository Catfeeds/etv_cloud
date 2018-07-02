<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/2
 * Time: 14:04
 */

namespace app\api\controller;
use app\common\controller\Api;
use think\Db;
use think\Exception;
use think\Log;

class Device extends Api
{
	// 无需登录的接口,*表示全部
	protected $noNeedLogin = ['*'];

	public function device_info()
	{
		if ($this->request->isPost()){
			$params = $this->request->post();
			if(!isset($params['mac']) || !isset($params['custom_cid']) || !isset($params['room']) || !isset($params['model']) || !isset($params['firmware_version'])){
				$this->error(__('Parameter error'), [], -1);
			}
			$Custom_obj = Db::name('custom')->where('custom_id','eq',$params['custom_cid'])->field('id')->find();
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
}