<?php

namespace app\admin\model;

use fast\Tree;
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

	/**
	 * 获取客户列表(不含有设备的不获取)
	 */
	public static function getCustomTreeList($where=[]){
		$list = collection(Db::name('custom')->where('id', 'in', $where)->field('id,custom_name as text')->select())->toArray();
		$device_info = Db::name('device_basics')->where('custom_id', 'in', $where)->distinct(true)->field('custom_id')->select();
		$custom_ids = array_column($device_info, 'custom_id');

		if(!empty($list)){
			foreach($list as $key=>$value){
				$list[$key]['id'] = 'custom_'.$value['id'];
				if(in_array($value['id'], $custom_ids)){
					$list[$key]['children'] = true;
				}else {
					unset($list[$key]);
				}
			}
		}
		return $list;
	}

	/**
	 * 获取设备列表
	 */
	public static function getDeviceTreeList($where){
		$list = Db::name('device_basics')->where($where)->field('id,mac,room')->select();
		$node_list = [];
		if(!empty($list)){
			foreach($list as $key=>$value){
				$node_list[] = [
					'id' =>  $value['id'],
					'text'  =>  $value['mac'].'('.$value['room'].') ',
				];
			}
		}
		return $node_list;

	}

	/**
	 * 处理字符串
	 * @param array $data
	 * @param string $treated_character
	 * @param string $repalce_character
	 * @return array
	 */
	public static function handle_character(array $data, $treated_character='custom_', $repalce_character=''){
		if(empty($data))
			return [];
		foreach($data as $key=>$value){
			$return[] = str_replace($treated_character, $repalce_character, $value);
		}
		return $return;
	}

	/**
	 * 数据集合转为树状结构形式
	 * @param $list
	 * @param string $pk
	 * @param string $pid
	 * @param string $child
	 * @param int $root
	 * @return array
	 */
	static function list_to_tree($list, $pk='id', $pid = 'pid', $child = 'children', $root = 0) {
		// 创建Tree
		$tree = array();
		if(is_array($list)) {
			// 创建基于主键的数组引用
			$refer = array();
			foreach ($list as $key => $data) {
				$refer[$data[$pk]] =& $list[$key];
			}
			foreach ($list as $key => $data) {
				// 判断是否存在parent
				$parentId =  $data[$pid];
				if ($root == $parentId) {
					$tree[] =& $list[$key];
				}else{
					if (isset($refer[$parentId])) {
						$parent =& $refer[$parentId];
						$parent[$child][] =& $list[$key];
					}
				}
			}
		}
		return $tree;
	}

}
