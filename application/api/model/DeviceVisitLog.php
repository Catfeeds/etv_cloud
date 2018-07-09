<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 16:05
 */

namespace app\api\model;
use think\Db;
use think\Exception;
use think\Log;
use think\Model;

class DeviceVisitLog extends Model
{
	public static function record()
	{
		$path = request()->path();

		if(!in_array($path,['api/custom/custom_list', 'api/custom/audit_login', 'api/custom/audit_process'])){
			$mac = request()->param('mac');
			$devise_obj = Db::name('device_basics')->where('mac','eq',$mac)->field('id')->find();
			$devise_id = isset($devise_obj['id'])? $devise_obj['id']: 0;
			$partition_data = ['mac_id'=>$devise_id];
			$rule = ['type'=>'mod', 'num'=>5];
			$insert_data['mac'] = $mac;
			$insert_data['mac_id'] = $devise_id;
			$insert_data['message'] = '调用 '.__($path).' 接口';
			$insert_data['post_time'] = time();
			try{
				Db::name('DeviceVisitLog')->partition($partition_data, 'mac_id',$rule)->insert($insert_data);
			}catch (\Exception $e){
				Log::write("错误信息为:".$e->getMessage());
				Log::save();
			}
		}else{
			if($path == 'api/custom/audit_process'){

			}
		}


	}
}