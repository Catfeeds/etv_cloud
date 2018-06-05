<?php

namespace app\admin\model;

use think\Db;
use think\Model;

class DeviceBasics extends Model
{
    // 表名
    protected $name = 'device_basics';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    public function custom(){
        return $this->belongsTo('Custom', 'custom_id', 'id')->setEagerlyType(0);
    }

	public function wifiset(){
		return $this->belongsTo('device_wifiset', 'mac', 'mac')->setEagerlyType(1);
	}

	public static function getTreeList($selected = [], $where_column = [])
	{
		$field = 'id,custom_id,mac,room';
		$ruleList = collection(self::where('id','in',$where_column)->field($field)->select())->toArray();
		$custom_list = collection(Db::name('custom')->where('id','in','1,2,3')->field('id,custom_name')->select())->toArray();
		$nodeList = [];

		foreach($custom_list as $custom_key => $custom_value){
			$nodeList[] = array('id' => 'custom_id'.$custom_value['id'],
				'parent' => '#',
				'text' => $custom_value['custom_name'],
			    'state' => ['selected'=>false]);
		}

		foreach ($ruleList as $k => $v)
		{
			$nodeList[] = array('id' =>$v['id'],
				'parent' => 'custom_id'.$v['custom_id'],
				'text' => $v['mac'].'('.$v['room'].')',
				'state' => ['selected'=>false]);
		}
		unset($v);
		return $nodeList;
	}

}
