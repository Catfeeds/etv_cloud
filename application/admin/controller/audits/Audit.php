<?php

namespace app\admin\controller\audits;


use app\common\controller\Backend;
use think\Db;

class Audit extends Backend
{
	protected $multiFields = 'audit_status';

	private function audit_function($ids, $table) {
		if ($ids)
		{
			if ($this->request->has('params'))
			{
				parse_str($this->request->post("params"), $values);
				$values = array_intersect_key($values, array_flip(is_array($this->multiFields) ? $this->multiFields : explode(',', $this->multiFields)));
				if ($values)
				{
					$count = Db::name($table)->where('id','in',$ids)->update($values);
					if ($count)
					{
						$this->success();
					}
					else
					{
						$this->error(__('No rows were updated'));
					}
				}
				else
				{
					$this->error(__('You have no permission'));
				}
			}
		}
		$this->error(__('Parameter %s can not be empty', 'ids'));
	}

	/**
	 * 欢迎图片模块审核
	 * @param string $ids
	 */
	public function welcome_audit($ids = ""){
		if ($ids)
		{
			$this->audit_function($ids,'welcome_resource');
		}
		$this->error(__('Parameter %s can not be empty', 'ids'));
	}

	/**
	 * 跳转视频模块审核
	 * @param string $ids
	 */
	public function jump_audit($ids = "") {
		if ($ids)
		{
			$this->audit_function($ids,'jump_resource');
		}
		$this->error(__('Parameter %s can not be empty', 'ids'));
	}

	/**
	 * 宣传轮播模块审核
	 * @param string $ids
	 */
	public function propaganda_audit($ids = "") {
		if ($ids)
		{
			$this->audit_function($ids,'propaganda_resource');
		}
		$this->error(__('Parameter %s can not be empty', 'ids'));
	}

	/**
	 * 弹窗广告模块审核
	 * @param string $ids
	 */
	public function popup_audit($ids = "") {
		if ($ids)
		{
			$this->audit_function($ids,'popup_resource');
		}
		$this->error(__('Parameter %s can not be empty', 'ids'));
	}

	/**
	 * 简易广告模块审核
	 * @param string $ids
	 */
	public function simplead_audit($ids = "") {
		if ($ids)
		{
			$this->audit_function($ids,'simplead_resource');
		}
		$this->error(__('Parameter %s can not be empty', 'ids'));
	}

	public function col_resource_audit($ids = "") {
		if ($ids)
		{
			$this->audit_function($ids,'col_resource');
		}
		$this->error(__('Parameter %s can not be empty', 'ids'));
	}
}