<?php
/**
 * 资源绑定类
 * 绑定资源与客户
 * 欢迎资源绑定,
 */

namespace app\admin\controller\resources;

use app\admin\model\Custom;
use app\common\controller\Backend;
use think\Db;
use think\Exception;

class Bindresource extends Backend
{
	protected $admin_had_custom = 0; //账号绑定的客户

	public function _initialize()
	{
		parent::_initialize();

		//获取账号对应的custom_id
		$admin_id = $this->auth->id;
		$allot_config = Db::name('config')->where('name','eq','resource_allot')->field('value')->find();
		$allot_config = json_decode($allot_config['value'],true);
		if(!empty($allot_config)){
			$admin_key = array_unique(array_keys($allot_config)); //特殊账号ID集合
			// 判断操作账号是否为特殊账号
			if(in_array($admin_id,$admin_key)){
				if($allot_config[$admin_id] == "*"){  //获取所有客户
					$this->admin_had_custom = [];
				}else{                                //获取特殊账号对应的客户
					$this->admin_had_custom = explode(",", $allot_config[$admin_id]);
				}
			}
		}

		if($this->admin_had_custom == 0){ //非特殊账号
			$custom_id = Db::name('admin_custom_bind')->where('admin_id','eq',$admin_id)->find();// 绑定表
			if(isset($custom_id['custom_id']) && !empty($custom_id['custom_id'])){  //获取绑定客户
				$this->admin_had_custom = explode(",", $custom_id['custom_id']);
			}else{
				$this->admin_had_custom = [0];
			}
		}
	}

	/**
	 * 判断绑定数据客户ID是否合法
	 */
	public function identify_customid($params){
		if(!isset($params['custom_ids'])){ //提交为空
			return false;
		}
		if(empty($this->admin_had_custom)){ // *全部通过
			return true;
		}elseif($this->admin_had_custom['0'] == 0){ // 0全部不通过
			return false;
		}else{ //判断集合
			$diff = array_diff(explode(",", $params['custom_ids']), $this->admin_had_custom);
			if(empty($diff)){
				return true;
			}else{
				return false;
			}
		}
	}

	/**
	 * 欢迎图片资源分配至客户
	 */
	public function welcome_allot($ids=NULL){
		if(!$ids)
			$this->error(__('No Results were found'));

		$welcome_custom = Db::name('welcome_custom')->where('rid','eq',$ids)->field('custom_id')->select();
		if(!empty($welcome_custom)){
			$welcome_custom = array_column($welcome_custom,'custom_id');
		}

		if($this->request->isPost()){
			$params = $this->request->post("row/a");
			if ($params)
			{
				if(empty($params['custom_ids'])){
					$result = Db::name('welcome_custom')->where('rid','eq',$ids)->delete();
					if($result !== false){
						$this->success();
					}else{
						$this->error();
					}
				}
				$identify_result = $this->identify_customid($params);
				if($identify_result == false){
					$this->error(__('Parameter error'));
				}
				$custom_ids = explode(",", $params['custom_ids']);
				$add_data = []; //新增数据
				$i = 0;
				foreach($custom_ids as $key=>$value){
					if(in_array($value, $welcome_custom)){
						$welcome_custom = array_diff($welcome_custom, [$value]); //剩余为删除数据
					}else{
						$add_data[$i]['custom_id'] = $value;
						$add_data[$i]['rid'] = $ids;
						$add_data[$i]['updatetime'] = time();
						$add_data[$i]['status'] = 'hidden';
						++$i;
					}
				}

				try
				{
					Db::startTrans();
					if(!empty($add_data)){
						Db::name('welcome_custom')->insertAll($add_data);
					}
					if(!empty($welcome_custom)){
						$del_where['rid'] = $ids;
						$del_where['custom_id'] = array('in', $welcome_custom);
						Db::name('welcome_custom')->where($del_where)->delete();
					}
				}
				catch (Exception $e)
				{
					Db::rollback();
					$this->error(__('Operation failed'));
				}
				Db::commit();
				$this->success();
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}

		$nodeList = Custom::getTreeList($welcome_custom, $this->admin_had_custom);
		$this->assign("nodeList", $nodeList);
		return $this->view->fetch('allot');
	}

	/**
	 * 跳转资源分配至客户
	 */
	public function jump_allot($ids=NULL){
		if(!$ids)
			$this->error(__('No Results were found'));

		$jump_custom = Db::name('jump_custom')->where('rid','eq',$ids)->field('custom_id')->select();
		if(!empty($jump_custom)){
			$jump_custom = array_column($jump_custom,'custom_id');
		}

		if($this->request->isPost()){
			$params = $this->request->post("row/a");
			if ($params)
			{
				if(empty($params['custom_ids'])){
					$result = Db::name('jump_custom')->where('rid','eq',$ids)->delete();
					if($result !== false){
						$this->success();
					}else{
						$this->error();
					}
				}
				$identify_result = $this->identify_customid($params);
				if($identify_result == false){
					$this->error(__('Parameter error'));
				}
				$custom_ids = explode(",", $params['custom_ids']);
				$add_data = []; //新增数据
				$i = 0;
				foreach($custom_ids as $key=>$value){
					if(in_array($value, $jump_custom)){
						$jump_custom = array_diff($jump_custom, [$value]); //剩余为删除数据
					}else{
						$add_data[$i]['custom_id'] = $value;
						$add_data[$i]['rid'] = $ids;
						$add_data[$i]['status'] = 'hidden';
						++$i;
					}
				}

				try
				{
					Db::startTrans();
					if(!empty($add_data)){
						Db::name('jump_custom')->insertAll($add_data);
					}
					if(!empty($jump_custom)){
						$del_where['rid'] = $ids;
						$del_where['custom_id'] = array('in', $jump_custom);
						Db::name('jump_custom')->where($del_where)->delete();
					}
				}
				catch (Exception $e)
				{
					Db::rollback();
					$this->error(__('Operation failed'));
				}
				Db::commit();
				$this->success();
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}

		$nodeList = Custom::getTreeList($jump_custom, $this->admin_had_custom);
		$this->assign("nodeList", $nodeList);
		return $this->view->fetch('allot');
	}

	/**
	 * 宣传轮播资源分配至客户
	 */
	public function propaganda_allot($ids=NULL){
		if(!$ids)
			$this->error(__('No Results were found'));

		$propaganda_custom = Db::name('propaganda_custom')->where('rid','eq',$ids)->field('custom_id')->select();
		if(!empty($propaganda_custom)){
			$propaganda_custom = array_column($propaganda_custom,'custom_id');
		}

		if($this->request->isPost()){
			$params = $this->request->post("row/a");
			if ($params)
			{
				if(empty($params['custom_ids'])){
					$result = Db::name('propaganda_custom')->where('rid','eq',$ids)->delete();
					if($result !== false){
						$this->success();
					}else{
						$this->error();
					}
				}
				$identify_result = $this->identify_customid($params);
				if($identify_result == false){
					$this->error(__('Parameter error'));
				}
				$custom_ids = explode(",", $params['custom_ids']);
				$add_data = []; //新增数据
				$i = 0;
				foreach($custom_ids as $key=>$value){
					if(in_array($value, $propaganda_custom)){
						$propaganda_custom = array_diff($propaganda_custom, [$value]); //剩余为删除数据
					}else{
						$add_data[$i]['custom_id'] = $value;
						$add_data[$i]['rid'] = $ids;
						$add_data[$i]['status'] = 'hidden';
						$add_data[$i]['weigh'] = '0';
						++$i;
					}
				}

				try
				{
					Db::startTrans();
					if(!empty($add_data)){
						Db::name('propaganda_custom')->insertAll($add_data);
					}
					if(!empty($propaganda_custom)){
						$del_where['rid'] = $ids;
						$del_where['custom_id'] = array('in', $propaganda_custom);
						Db::name('propaganda_custom')->where($del_where)->delete();
					}
				}
				catch (Exception $e)
				{
					Db::rollback();
					$this->error(__('Operation failed'));
				}
				Db::commit();
				$this->success();
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}

		$nodeList = Custom::getTreeList($propaganda_custom, $this->admin_had_custom);
		$this->assign("nodeList", $nodeList);
		return $this->view->fetch('allot');
	}

	/**
	 * 弹窗广告资源分配至客户
	 */
	public function popup_allot($ids=NULL){
		if(!$ids)
			$this->error(__('No Results were found'));

		$popup_custom = Db::name('popup_custom')->where('rid','eq',$ids)->field('custom_id')->select();
		if(!empty($popup_custom)){
			$popup_custom = array_column($popup_custom,'custom_id');
		}

		if($this->request->isPost()){
			$params = $this->request->post("row/a");
			if ($params)
			{
				if(empty($params['custom_ids'])){
					$result = Db::name('popup_custom')->where('rid','eq',$ids)->delete();
					if($result !== false){
						$this->success();
					}else{
						$this->error();
					}
				}
				$identify_result = $this->identify_customid($params);
				if($identify_result == false){
					$this->error(__('Parameter error'));
				}
				$custom_ids = explode(",", $params['custom_ids']);
				$add_data = []; //新增数据
				$i = 0;
				foreach($custom_ids as $key=>$value){
					if(in_array($value, $popup_custom)){
						$popup_custom = array_diff($popup_custom, [$value]); //剩余为删除数据
					}else{
						$add_data[$i]['custom_id'] = $value;
						$add_data[$i]['rid'] = $ids;
						++$i;
					}
				}

				try
				{
					Db::startTrans();
					if(!empty($add_data)){
						Db::name('popup_custom')->insertAll($add_data);
					}
					if(!empty($popup_custom)){
						$del_where['rid'] = $ids;
						$del_where['custom_id'] = array('in', $popup_custom);
						Db::name('popup_custom')->where($del_where)->delete();
					}
				}
				catch (Exception $e)
				{
					Db::rollback();
					$this->error(__('Operation failed'));
				}
				Db::commit();
				$this->success();
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}

		$nodeList = Custom::getTreeList($popup_custom, $this->admin_had_custom);
		$this->assign("nodeList", $nodeList);
		return $this->view->fetch('allot');
	}

	/**
	 * 栏目分配至客户
	 * 仅针对一级栏目进行分配
	 */
	public function column_allot($ids=NULL){
		if(!$ids)
			$this->error(__('No Results were found'));

		$column_info = Db::name('column')->where('id','eq',$ids)->field('level')->find();
		if($column_info['level'] != 1)
			$this->error(__('Allot trips'));

		$column_custom = Db::name('column_custom')->where('rid','eq',$ids)->field('custom_id')->select();
		if(!empty($column_custom)){
			$column_custom = array_column($column_custom,'custom_id');
		}

		if($this->request->isPost()){
			$params = $this->request->post("row/a");
			if ($params)
			{
				if(empty($params['custom_ids'])){
					$result = Db::name('column_custom')->where('rid','eq',$ids)->delete();
					if($result !== false){
						$this->success();
					}else{
						$this->error();
					}
				}
				$identify_result = $this->identify_customid($params);
				if($identify_result == false){
					$this->error(__('Parameter error'));
				}
				$custom_ids = explode(",", $params['custom_ids']);
				$add_data = []; //新增数据
				$i = 0;
				foreach($custom_ids as $key=>$value){
					if(in_array($value, $column_custom)){
						$column_custom = array_diff($column_custom, [$value]); //剩余为删除数据
					}else{
						$add_data[$i]['custom_id'] = $value;
						$add_data[$i]['rid'] = $ids;
						++$i;
					}
				}

				//添加排序和状态
				if(!empty($add_data)){

				}

				try
				{
					Db::startTrans();
					if(!empty($add_data)){
						Db::name('column_custom')->insertAll($add_data);
					}
					if(!empty($column_custom)){
						$del_where['rid'] = $ids;
						$del_where['custom_id'] = array('in', $column_custom);
						Db::name('column_custom')->where($del_where)->delete();
					}
				}
				catch (Exception $e)
				{
					Db::rollback();
					$this->error(__('Operation failed'));
				}
				Db::commit();
				$this->success();
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}

		$nodeList = Custom::getTreeList($column_custom, $this->admin_had_custom);
		$this->assign("nodeList", $nodeList);
		return $this->view->fetch('allot');
	}

	/**
	 * 简易广告分配至客户
	 */
	public function simplead_allot($ids=NULL){
		if(!$ids)
			$this->error(__('No Results were found'));

		$simplead_custom = Db::name('simplead_custom')->where('rid','eq',$ids)->field('custom_id')->select();
		if(!empty($simplead_custom)){
			$simplead_custom = array_column($simplead_custom,'custom_id');
		}

		if($this->request->isPost()){
			$params = $this->request->post("row/a");
			if ($params)
			{
				if(empty($params['custom_ids'])){
					$result = Db::name('simplead_custom')->where('rid','eq',$ids)->delete();
					if($result !== false){
						$this->success();
					}else{
						$this->error();
					}
				}
				$identify_result = $this->identify_customid($params);
				if($identify_result == false){
					$this->error(__('Parameter error'));
				}
				$custom_ids = explode(",", $params['custom_ids']);
				$add_data = []; //新增数据
				$i = 0;
				foreach($custom_ids as $key=>$value){
					if(in_array($value, $simplead_custom)){
						$simplead_custom = array_diff($simplead_custom, [$value]); //剩余为删除数据
					}else{
						$add_data[$i]['custom_id'] = $value;
						$add_data[$i]['rid'] = $ids;
						$add_data[$i]['updatetime'] = time();
						$add_data[$i]['status'] = 'hidden';
						++$i;
					}
				}

				try
				{
					Db::startTrans();
					if(!empty($add_data)){
						Db::name('simplead_custom')->insertAll($add_data);
					}
					if(!empty($simplead_custom)){
						$del_where['rid'] = $ids;
						$del_where['custom_id'] = array('in', $simplead_custom);
						Db::name('simplead_custom')->where($del_where)->delete();
					}
				}
				catch (Exception $e)
				{
					Db::rollback();
					$this->error(__('Operation failed'));
				}
				Db::commit();
				$this->success();
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}

		$nodeList = Custom::getTreeList($simplead_custom, $this->admin_had_custom);
		$this->assign("nodeList", $nodeList);
		return $this->view->fetch('allot');
	}

	public function time_app_allot($ids=NULL){
		if(!$ids)
			$this->error(__('No Results were found'));

		$time_app_custom_list = Db::name('timing_app_custom')->where('time_app_id','eq',$ids)->field('custom_id')->find();
		if(!empty($time_app_custom_list) && !empty($time_app_custom_list['custom_id'])){
			$time_app_custom = explode(",", $time_app_custom_list['custom_id']);
		}else{
			$time_app_custom = [];
		}

		if($this->request->isPost()){
			$params = $this->request->post("row/a");
			if ($params)
			{
				if(!empty($params['custom_ids'])){
					$identify_result = $this->identify_customid($params);
					if($identify_result == false){
						$this->error(__('Parameter error'));
					}
				}

				try{
					Db::name('timing_app_custom')->where('time_app_id','eq',$ids)->update(['custom_id'=>$params['custom_ids']]);
				}catch (\Exception $e){
					$this->error(__('Operation failed'));
				}
				$this->success();
			}
			$this->error(__('Parameter %s can not be empty', ''));
		}

		$nodeList = Custom::getTreeList($time_app_custom, $this->admin_had_custom);
		$this->assign("nodeList", $nodeList);
		return $this->view->fetch('allot');
	}
}

