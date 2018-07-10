<?php

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
		$not_device_visit_path = [
			'api/custom/custom_list',
			'api/custom/audit_login',
			'api/custom/audit_process_notcolumn',
			'api/custom/audit_process_column_resource'
		];

		$audit_process_path = [
			'api/custom/audit_process_notcolumn',
			'api/custom/audit_process_column_resource'
		];

		if(!in_array($path,$not_device_visit_path)){
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
				Log::write("接口日志切面出错,错误信息为:".$e->getMessage());
				Log::save();
			}
		}else{
			// 发布接口的日志记录
			if(in_array($path,$audit_process_path)){
				$token = request()->header();
				$audit_token = $token['audit_token'];
				$param = request()->param();
				if('api/custom/audit_process_column_resource' == $path){
					if(isset($param['custom_id'])){
						unset($param['custom_id']);
					}
					$param['audit_type'] = 'release_type';
					$param['audit_module'] = 'module_col_resource';
				}
				$admin_obj = Db::name('admin')->where('token','eq',$audit_token)->field('id')->find();

				if(!empty($admin_obj)){
					$param['admin_id'] = $admin_obj['id'];
					$param['run_time'] = time();
					$field = 'admin_id,run_time,audit_type,audit_module,audit_list_id,audit_value';
					try{
						Db::name('audit_process_log')->field($field)->insert($param);
					}catch (\Exception $e){
						Log::write("接口日志切面出错,错误信息为:".$e->getMessage());
						Log::save();
					}
				}
			}
		}


	}
}