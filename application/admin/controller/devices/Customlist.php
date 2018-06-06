<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/30
 * Time: 16:08
 */

namespace app\admin\controller\devices;

use think\Controller;
use think\Db;

class Customlist extends Controller
{
	public function _initialize()
	{
		parent::_initialize();
	}

	/**
	 * 获取账号绑定的客户列表 (设备用)
	 * 管理员账号对应的客户列表如果为* 则返回GET_ALL标识
	 */
	public function custom_id_device($admin_id){
		$admin_custom_bind = Db::name('admin_custom_bind')->where('admin_id','eq',$admin_id)->find();

		// 权限判断
		if(empty($admin_custom_bind)){ //绑定表为空
			$content_set_config = Db::name('config')->where('name','eq','device_manage')->field('value')->find();
			if(!empty($content_set_config)){
				$content_set_config = json_decode($content_set_config['value'], true);
				$custom_key = array_keys($content_set_config);
				if(in_array($admin_id, $custom_key)){
					if($content_set_config[$admin_id] == "*"){  //查询全部
						return config('get all');
					}else{
						return explode(",", $content_set_config[$admin_id]);
					}
				}
			}else{
				return [0];
			}
		}else{
			return explode(",", $admin_custom_bind['custom_id']);
		}
	}

	/**
	 * 获取账号绑定的客户列表
	 * 返回包括客户ID和客户名称
	 */
	public function custom_list($admin_id){
		$admin_custom_bind = Db::name('admin_custom_bind')->where('admin_id','eq',$admin_id)->find();

		// 权限判断
		if(empty($admin_custom_bind)){ //绑定表为空
			$content_set_config = Db::name('config')->where('name','eq','device_manage')->field('value')->find();
			if(!empty($content_set_config)){
				$content_set_config = json_decode($content_set_config['value'], true);
				$custom_key = array_keys($content_set_config);
				if(in_array($admin_id, $custom_key)){
					if($content_set_config[$admin_id] == "*"){  //查询全部
						return Db::name('custom')->cache(true,600)->field('id,custom_name')->select();
					}else{
						$custom_where = explode(",", $content_set_config[$admin_id]);
						return Db::name('custom')->where('id', 'in', $custom_where)->field('id,custom_name')->select();
					}
				}
			}
		}else{
			$custom_where = explode(",", $admin_custom_bind['custom_id']);
			return Db::name('custom')->where('id', 'in', $custom_where)->field('id,custom_name')->select();
		}
	}
}