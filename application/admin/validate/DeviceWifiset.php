<?php

namespace app\admin\validate;

use think\Validate;

class DeviceWifiset extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'wifi_ssid|WIFI账号'     =>  'require|max:50',
        'wifi_passwd|WIFI密码'   =>  'min:8|max:50|alphaNum',
        'wifi_psk_type'          =>  'in:psk2,none|check_passwd',
        'wifi_hot_spot'          =>  'in:open,close',
        'status'                 =>  'in:normal,hidden',
	    'mac_ids'                =>  'check_mac_ids'
    ];
    /**
     * 提示消息
     */
    protected $message = [
	    'wifi_hot_spot'     =>  'Wifi_hot_spot_tips',
	    'wifi_psk_type.in'  =>  'Wifi_psk_type_in',
	    'status'            =>  'Status_tips',
	    'mac_ids'           =>  'Mac_tips'

    ];

    public function check_passwd($params, $rule, $data){
        if($data['wifi_psk_type'] == 'psk2'){
            if(empty($data['wifi_passwd'])){
                return __('Password is need');
            }else{
                return true;
            }
        }else{
	        return true;
        }
    }

	public function check_mac_ids($params, $rule, $data){
		$mac_ids = explode(",", $data['mac_ids']);
		foreach($mac_ids as $value) {
			if(!is_numeric($value) || $value<1){
				return __('Mac_tips');
			}
		}
		return true;
	}

    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [],
        'edit' => [],
    ];
    
}
