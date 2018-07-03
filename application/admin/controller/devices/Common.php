<?php

namespace app\admin\controller\devices;

use app\admin\controller\contentset\Customlist;
use app\admin\model\DeviceBasics;
use app\common\controller\Backend;
use think\Db;


class Common extends Backend
{
	protected $admin_id = null;

	protected $noNeedLogin = ['get_tree_list'];
	protected $noNeedRight = ['get_tree_list'];

	public function _initialize()
	{
		parent::_initialize();
		$this->admin_id = $this->auth->id;
	}

	/**
	 * 客户与设备组建的树状图
	 */
	public function get_tree_list($id=null){
		if(empty($id)){
			$this->error(__('Unknown data format'));
		}
		$nodelist = [];
		if("#" == $id){
			$Customlist_class = new Customlist();
			$custom_list = $Customlist_class->custom_list($this->admin_id);
			$nodelist = DeviceBasics::getCustomTreeList(array_column($custom_list, 'id'));
		}elseif(is_numeric(substr($id, 7))){
			$where_device['custom_id'] = substr($id, 7);
			$nodelist = DeviceBasics::getDeviceTreeList($where_device);
		}
		return json($nodelist);
	}

	/**
	 * 获取mac数据集合
	 * @param string $custom_list 客户集合 形式:custom1,custom2
	 * @param string $mac_list MAC集合 形式:mac1,mac2
	 */
	public function maclist_by_custom_mac($custom_list='', $mac_list=''){
		$where_basics = [];
		$query_basics = Db::name('device_basics');
		// 选中设备的条件
		if(!empty($custom_list)){
			$custom_id = DeviceBasics::handle_character(explode(",", $custom_list));
			$where_basics['custom_id'] = ['in', $custom_id];
			$query_basics->where($where_basics);
		}
		if(!empty($mac_list)){
			$where_basics['id'] = ['in', $mac_list];
			$query_basics->whereOr($where_basics);
		}
		if(empty($where_basics))
			return false;

		return $query_basics->field('mac')->select();
	}
}