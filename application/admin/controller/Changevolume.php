<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/18
 * Time: 9:13
 */

namespace app\admin\controller;

use app\common\controller\Backend;
use think\db;
use think\Log;

class Changevolume extends Backend
{
	/**
	 * 增加账号对应容量方法
	 * @param int $id
	 * @param int $volumn
	 * @return bool
	 * @throws \think\Exception
	 */
	public function inc_capacity($id=0, $volumn=0){
		if($id){
			$where['admin_id'] = $id;
		}else{
			return false;
		}
		$vo = Db::name('admin_capacity')->where($where)->field(['(application_capacity-used_capacity)'=>'residue_capacity'])->find();
		if($vo['residue_capacity'] - $volumn < 0){
			return false;
		}
		try{
			Db::name('admin_capacity')->where($where)->setInc('used_capacity', $volumn);
		}catch(\Exception $e){
			Log::write('新增使用容量出错,amdin_id:'.$id);
			Log::save();
			return false;
		}
		return true;
	}

	public function dec_capacity($id=0, $volumn=0){
		if($id){
			$where['admin_id'] = $id;
		}else{
			return false;
		}
		try{
			Db::name('admin_capacity')->where($where)->setDec('used_capacity', $volumn);
			return true;
		}catch(Exception $e){
			Log::record(json_encode($e->getError()));
			return false;
		}
	}
}