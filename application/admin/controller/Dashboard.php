<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Config;
use think\Db;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{
    /**
     * 查看
     */
    public function index()
    {
    	$admin_id = $this->auth->id;
	    $admin_custom_bind = Db::name('admin_custom_bind')->where('admin_id','eq',$admin_id)->find();

	    if(!empty($admin_custom_bind) && !empty($admin_custom_bind['custom_id'])){ //含有绑定客户
	    	$count_custom = count(explode(",", $admin_custom_bind['custom_id']));
		    $device_info = Db::name('device_basics')->where('custom_id','in',$admin_custom_bind['custom_id'])->field('id,status')->select();
		    $count_device = count($device_info);
		    if(0 != $count_device){ //是否含有设备
			    $device_status_info = array_column($device_info, 'status');
			    $status_info = array_count_values($device_status_info);
			    $count_normal = $status_info['normal'];
		    }else{
			    $count_normal = 0;
		    }
	    }else{
		    $auth_group_access_info = Db::name('auth_group_access')->where('uid','eq',$this->auth->id)->field('group_id')->select();
		    $group_ids = array_column($auth_group_access_info,'group_id');

		    $dashboard_config = Db::name('config')->where('name','eq','dashboard_group')->field('value')->find();
		    $dashboard_group_ids = explode(",", $dashboard_config['value']);
			if(!array_diff($group_ids, $dashboard_group_ids)){ //规定账号所在的组,都在设定可查看组的列表内
				$count_custom = Db::name('custom')->count();
				$device_info = Db::name('device_basics')->field('status')->select();
				$count_device = count($device_info);
				$device_status_info = array_column($device_info, 'status');
				$status_info = array_count_values($device_status_info);
				$count_normal = $status_info['normal'];
			}else{
				$count_custom = 0;
				$count_device = 0;
				$count_normal = 0;
			}
	    }

	    $this->view->assign([
            'count_custom'      => $count_custom,
		    'count_device'      => $count_device,
		    'count_normal'      => $count_normal,
        ]);

        return $this->view->fetch();
    }

}
