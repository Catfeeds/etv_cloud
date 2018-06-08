<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/15
 * Time: 16:56
 */

namespace app\admin\controller\contentset;

use think\Controller;
use think\Db;

class Customlist extends Controller
{
	protected $noNeedLogin = [];
	protected $noNeedRight = [];

	public function _initialize()
	{
		parent::_initialize();
	}

	/**
	 * 获取账号绑定的客户列表
	 */
	public function custom_list($admin_id){
		$admin_custom_bind = Db::name('admin_custom_bind')->where('admin_id','eq',$admin_id)->find();

		// 权限判断
		if(empty($admin_custom_bind)){ //绑定表为空
			$content_set_config = Db::name('config')->where('name','eq','content_set')->field('value')->find();
			if(!empty($content_set_config)){
				$content_set_config = json_decode($content_set_config['value'], true);
				$custom_key = array_keys($content_set_config);
				if(in_array($admin_id, $custom_key)){
					if($content_set_config[$admin_id] == "*"){  //查询全部
						return Db::name('custom')->field('id,custom_name')->select();
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

	/**
	 * 获取账号绑定的客户列表ID
	 */
	public function custom_id($admin_id){
		$admin_custom_bind = Db::name('admin_custom_bind')->where('admin_id','eq',$admin_id)->find();

		// 权限判断
		if(empty($admin_custom_bind)){ //绑定表为空
			$content_set_config = Db::name('config')->where('name','eq','content_set')->field('value')->find();
			if(!empty($content_set_config)){
				$content_set_config = json_decode($content_set_config['value'], true);
				$custom_key = array_keys($content_set_config);
				if(in_array($admin_id, $custom_key)){
					if($content_set_config[$admin_id] == "*"){  //查询全部
						$custom_id_list = Db::name('custom')->field('id')->select();
						return array_column($custom_id_list, 'id');
					}else{
						return explode(",", $content_set_config[$admin_id]);
					}
				}
			}
		}else{
			return explode(",", $admin_custom_bind['custom_id']);
		}
	}

	/**
	 * 获取账号绑定的客户列表
	 * 管理员账号 对应的客户列表如果为* 则不获取客户列表ID
	 */
	public function custom_id_by_column($admin_id){
		$admin_custom_bind = Db::name('admin_custom_bind')->where('admin_id','eq',$admin_id)->find();

		// 权限判断
		if(empty($admin_custom_bind)){ //绑定表为空
			$content_set_config = Db::name('config')->where('name','eq','content_set_column')->field('value')->find();
			if(!empty($content_set_config)){
				$content_set_config = json_decode($content_set_config['value'], true);
				$custom_key = array_keys($content_set_config);
				if(in_array($admin_id, $custom_key)){
					if($content_set_config[$admin_id] == "*"){  //不可全部查询
						return ["0"];
					}else{
						return explode(",", $content_set_config[$admin_id]);
					}
				}
			}
		}else{
			return explode(",", $admin_custom_bind['custom_id']);
		}
	}
}